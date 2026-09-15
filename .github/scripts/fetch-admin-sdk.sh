#!/usr/bin/env sh
# Скачивает текущий zip DevCraft Admin с витрины и кладёт sdk/dle (модели Schema).
# /download отдаёт GitHub-снимок репозитория: mhadmin-rel-{версия}.zip
# (корень архива mhadmin-rel-200.4.1/upload/devcraft/src/sdk/dle).
# Env: ADMIN_DOWNLOAD_URL, DLE_ADMIN_SDK (каталог назначения = …/sdk/dle)
set -eu

URL="${ADMIN_DOWNLOAD_URL:-https://devcraft.club/downloads/devcraft-admin-panel.4/download}"
DEST="${DLE_ADMIN_SDK:-}"

if [ -z "$DEST" ]; then
	echo "DLE_ADMIN_SDK не задан" >&2
	exit 1
fi

TMP="$(mktemp -d)"
cleanup() { rm -rf "$TMP"; }
trap cleanup EXIT

echo "Admin SDK: скачиваю ${URL}" >&2
# -J: имя файла из Content-Disposition → mhadmin-rel-200.4.1.zip
(
	cd "$TMP"
	curl -fsSL --retry 3 --retry-delay 2 -L -J -O "$URL"
)

ZIP="$(find "$TMP" -maxdepth 1 -type f \( -name '*.zip' -o -name '*.ZIP' \) | head -n 1)"
if [ -z "$ZIP" ]; then
	echo "Admin SDK: zip не появился в ${TMP}" >&2
	ls -la "$TMP" >&2 || true
	exit 1
fi

echo "Admin SDK: файл $(basename "$ZIP")" >&2

if ! unzip -t "$ZIP" >/dev/null 2>&1; then
	echo "Admin SDK: ответ не zip (Cloudflare / вход?)" >&2
	head -c 200 "$ZIP" >&2 || true
	exit 1
fi

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
