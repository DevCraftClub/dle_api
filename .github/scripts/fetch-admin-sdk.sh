#!/usr/bin/env sh
# Скачивает текущий zip DevCraft Admin с витрины и кладёт sdk/dle (модели Schema).
# /download отдаёт GitHub-снимок репозитория: mhadmin-rel-{версия}.zip
# (корень архива mhadmin-rel-200.4.1/upload/devcraft/src/sdk/dle).
# Env: ADMIN_DOWNLOAD_URL, ADMIN_ARCHIVE_URL, DLE_ADMIN_SDK, GITHUB_TOKEN
set -eu

URL="${ADMIN_DOWNLOAD_URL:-https://devcraft.club/downloads/devcraft-admin-panel.4/download}"
FALLBACK="${ADMIN_ARCHIVE_URL:-https://github.com/DevCraftClub/mhadmin/archive/refs/heads/rel/200.4.1.zip}"
DEST="${DLE_ADMIN_SDK:-}"

if [ -z "$DEST" ]; then
	echo "DLE_ADMIN_SDK не задан" >&2
	exit 1
fi

if ! command -v unzip >/dev/null 2>&1; then
	echo "Admin SDK: нет unzip" >&2
	exit 1
fi

TMP="$(mktemp -d)"
cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

ZIP="$TMP/admin.zip"

curl_zip() {
	src="$1"
	echo "Admin SDK: скачиваю ${src}" >&2
	# Фиксированное имя: -J/-O на CI часто даёт файл «download» без .zip.
	if [ -n "${GITHUB_TOKEN:-}" ] && printf '%s' "$src" | grep -q 'github.com'; then
		curl -fsSL --retry 3 --retry-delay 2 -L \
			-H "Authorization: Bearer ${GITHUB_TOKEN}" \
			-o "$ZIP" "$src"
	else
		curl -fsSL --retry 3 --retry-delay 2 -L -o "$ZIP" "$src"
	fi
}

zip_ok() {
	[ -s "$ZIP" ] && unzip -t "$ZIP" >/dev/null 2>&1
}

if ! curl_zip "$URL" || ! zip_ok; then
	echo "Admin SDK: витрина не zip, пробую снимок GitHub" >&2
	head -c 160 "$ZIP" >&2 || true
	echo >&2
	curl_zip "$FALLBACK"
fi

if ! zip_ok; then
	echo "Admin SDK: ответ не zip (Cloudflare / вход?)" >&2
	ls -la "$TMP" >&2 || true
	head -c 200 "$ZIP" >&2 || true
	exit 1
fi

echo "Admin SDK: файл $(wc -c < "$ZIP") байт" >&2

unzip -q "$ZIP" -d "$TMP/unpack"

SDK=""
# GitHub archive: mhadmin-rel-200.4.1/upload/devcraft/src/sdk/dle
for root in "$TMP/unpack"/mhadmin-rel-* "$TMP/unpack"/mhadmin-*; do
	[ -d "$root" ] || continue
	cand="$root/upload/devcraft/src/sdk/dle"
	if [ -d "$cand/Schema" ] && [ -d "$cand/Xfield/Schema" ]; then
		SDK="$cand"
		echo "Admin SDK: корень $(basename "$root")" >&2
		break
	fi
done

if [ -z "$SDK" ]; then
	while IFS= read -r dir; do
		if [ -d "$dir/Schema" ] && [ -d "$dir/Xfield/Schema" ]; then
			SDK="$dir"
			break
		fi
	done <<EOF
$(find "$TMP/unpack" -type d -path '*/upload/devcraft/src/sdk/dle')
EOF
fi

if [ -z "$SDK" ]; then
	echo "Admin SDK: в архиве нет mhadmin-rel-*/upload/devcraft/src/sdk/dle/Schema" >&2
	find "$TMP/unpack" -maxdepth 3 -type d >&2 || true
	exit 1
fi

rm -rf "$DEST"
mkdir -p "$DEST"
cp -a "$SDK"/. "$DEST"/

echo "Admin SDK: модели → ${DEST} (Schema=$(find "$DEST/Schema" -name '*.php' | wc -l))" >&2
