<<<<<<< New base: Update README.md
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `usergroups`.
 */
#[OA\Schema(schema: 'Usergroups')]
final class UsergroupsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (usergroups.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'group_name',
		type: 'string',
		description: 'Колонка usergroups.group_name',
	)]
	public string $group_name = '';
	#[OA\Property(
		property: 'allow_cats',
		type: 'string',
		description: 'Колонка usergroups.allow_cats',
	)]
	public string $allow_cats = '';
	#[OA\Property(
		property: 'allow_adds',
		type: 'integer',
		description: 'Колонка usergroups.allow_adds',
	)]
	public int $allow_adds = 1;
	#[OA\Property(
		property: 'cat_add',
		type: 'string',
		description: 'Колонка usergroups.cat_add',
	)]
	public string $cat_add = '';
	#[OA\Property(
		property: 'allow_admin',
		type: 'integer',
		description: 'Колонка usergroups.allow_admin',
	)]
	public int $allow_admin = 0;
	#[OA\Property(
		property: 'allow_addc',
		type: 'integer',
		description: 'Колонка usergroups.allow_addc',
	)]
	public int $allow_addc = 0;
	#[OA\Property(
		property: 'allow_editc',
		type: 'integer',
		description: 'Колонка usergroups.allow_editc',
	)]
	public int $allow_editc = 0;
	#[OA\Property(
		property: 'allow_delc',
		type: 'integer',
		description: 'Колонка usergroups.allow_delc',
	)]
	public int $allow_delc = 0;
	#[OA\Property(
		property: 'edit_allc',
		type: 'integer',
		description: 'Колонка usergroups.edit_allc',
	)]
	public int $edit_allc = 0;
	#[OA\Property(
		property: 'del_allc',
		type: 'integer',
		description: 'Колонка usergroups.del_allc',
	)]
	public int $del_allc = 0;
	#[OA\Property(
		property: 'moderation',
		type: 'integer',
		description: 'Колонка usergroups.moderation',
	)]
	public int $moderation = 0;
	#[OA\Property(
		property: 'allow_all_edit',
		type: 'integer',
		description: 'Колонка usergroups.allow_all_edit',
	)]
	public int $allow_all_edit = 0;
	#[OA\Property(
		property: 'allow_edit',
		type: 'integer',
		description: 'Колонка usergroups.allow_edit',
	)]
	public int $allow_edit = 0;
	#[OA\Property(
		property: 'allow_pm',
		type: 'integer',
		description: 'Колонка usergroups.allow_pm',
	)]
	public int $allow_pm = 0;
	#[OA\Property(
		property: 'max_pm',
		type: 'integer',
		description: 'Колонка usergroups.max_pm',
	)]
	public int $max_pm = 0;
	#[OA\Property(
		property: 'max_foto',
		type: 'string',
		description: 'Колонка usergroups.max_foto',
	)]
	public string $max_foto = '';
	#[OA\Property(
		property: 'allow_files',
		type: 'integer',
		description: 'Колонка usergroups.allow_files',
	)]
	public int $allow_files = 0;
	#[OA\Property(
		property: 'allow_hide',
		type: 'integer',
		description: 'Колонка usergroups.allow_hide',
	)]
	public int $allow_hide = 1;
	#[OA\Property(
		property: 'allow_short',
		type: 'integer',
		description: 'Колонка usergroups.allow_short',
	)]
	public int $allow_short = 0;
	#[OA\Property(
		property: 'time_limit',
		type: 'integer',
		description: 'Колонка usergroups.time_limit',
	)]
	public int $time_limit = 0;
	#[OA\Property(
		property: 'rid',
		type: 'integer',
		description: 'Колонка usergroups.rid',
	)]
	public int $rid = 0;
	#[OA\Property(
		property: 'allow_fixed',
		type: 'integer',
		description: 'Колонка usergroups.allow_fixed',
	)]
	public int $allow_fixed = 0;
	#[OA\Property(
		property: 'allow_feed',
		type: 'integer',
		description: 'Колонка usergroups.allow_feed',
	)]
	public int $allow_feed = 1;
	#[OA\Property(
		property: 'allow_search',
		type: 'integer',
		description: 'Колонка usergroups.allow_search',
	)]
	public int $allow_search = 1;
	#[OA\Property(
		property: 'allow_poll',
		type: 'integer',
		description: 'Колонка usergroups.allow_poll',
	)]
	public int $allow_poll = 1;
	#[OA\Property(
		property: 'allow_main',
		type: 'integer',
		description: 'Колонка usergroups.allow_main',
	)]
	public int $allow_main = 1;
	#[OA\Property(
		property: 'captcha',
		type: 'integer',
		description: 'Колонка usergroups.captcha',
	)]
	public int $captcha = 0;
	#[OA\Property(
		property: 'icon',
		type: 'string',
		description: 'Колонка usergroups.icon',
	)]
	public string $icon = '';
	#[OA\Property(
		property: 'allow_modc',
		type: 'integer',
		description: 'Колонка usergroups.allow_modc',
	)]
	public int $allow_modc = 0;
	#[OA\Property(
		property: 'allow_rating',
		type: 'integer',
		description: 'Колонка usergroups.allow_rating',
	)]
	public int $allow_rating = 1;
	#[OA\Property(
		property: 'allow_offline',
		type: 'integer',
		description: 'Колонка usergroups.allow_offline',
	)]
	public int $allow_offline = 0;
	#[OA\Property(
		property: 'allow_image_upload',
		type: 'integer',
		description: 'Колонка usergroups.allow_image_upload',
	)]
	public int $allow_image_upload = 0;
	#[OA\Property(
		property: 'allow_file_upload',
		type: 'integer',
		description: 'Колонка usergroups.allow_file_upload',
	)]
	public int $allow_file_upload = 0;
	#[OA\Property(
		property: 'allow_signature',
		type: 'integer',
		description: 'Колонка usergroups.allow_signature',
	)]
	public int $allow_signature = 0;
	#[OA\Property(
		property: 'allow_url',
		type: 'integer',
		description: 'Колонка usergroups.allow_url',
	)]
	public int $allow_url = 1;
	#[OA\Property(
		property: 'news_sec_code',
		type: 'integer',
		description: 'Колонка usergroups.news_sec_code',
	)]
	public int $news_sec_code = 1;
	#[OA\Property(
		property: 'allow_image',
		type: 'integer',
		description: 'Колонка usergroups.allow_image',
	)]
	public int $allow_image = 0;
	#[OA\Property(
		property: 'max_signature',
		type: 'integer',
		description: 'Колонка usergroups.max_signature',
	)]
	public int $max_signature = 0;
	#[OA\Property(
		property: 'max_info',
		type: 'integer',
		description: 'Колонка usergroups.max_info',
	)]
	public int $max_info = 0;
	#[OA\Property(
		property: 'admin_addnews',
		type: 'integer',
		description: 'Колонка usergroups.admin_addnews',
	)]
	public int $admin_addnews = 0;
	#[OA\Property(
		property: 'admin_editnews',
		type: 'integer',
		description: 'Колонка usergroups.admin_editnews',
	)]
	public int $admin_editnews = 0;
	#[OA\Property(
		property: 'admin_comments',
		type: 'integer',
		description: 'Колонка usergroups.admin_comments',
	)]
	public int $admin_comments = 0;
	#[OA\Property(
		property: 'admin_categories',
		type: 'integer',
		description: 'Колонка usergroups.admin_categories',
	)]
	public int $admin_categories = 0;
	#[OA\Property(
		property: 'admin_editusers',
		type: 'integer',
		description: 'Колонка usergroups.admin_editusers',
	)]
	public int $admin_editusers = 0;
	#[OA\Property(
		property: 'admin_wordfilter',
		type: 'integer',
		description: 'Колонка usergroups.admin_wordfilter',
	)]
	public int $admin_wordfilter = 0;
	#[OA\Property(
		property: 'admin_xfields',
		type: 'integer',
		description: 'Колонка usergroups.admin_xfields',
	)]
	public int $admin_xfields = 0;
	#[OA\Property(
		property: 'admin_userfields',
		type: 'integer',
		description: 'Колонка usergroups.admin_userfields',
	)]
	public int $admin_userfields = 0;
	#[OA\Property(
		property: 'admin_static',
		type: 'integer',
		description: 'Колонка usergroups.admin_static',
	)]
	public int $admin_static = 0;
	#[OA\Property(
		property: 'admin_editvote',
		type: 'integer',
		description: 'Колонка usergroups.admin_editvote',
	)]
	public int $admin_editvote = 0;
	#[OA\Property(
		property: 'admin_newsletter',
		type: 'integer',
		description: 'Колонка usergroups.admin_newsletter',
	)]
	public int $admin_newsletter = 0;
	#[OA\Property(
		property: 'admin_blockip',
		type: 'integer',
		description: 'Колонка usergroups.admin_blockip',
	)]
	public int $admin_blockip = 0;
	#[OA\Property(
		property: 'admin_banners',
		type: 'integer',
		description: 'Колонка usergroups.admin_banners',
	)]
	public int $admin_banners = 0;
	#[OA\Property(
		property: 'admin_rss',
		type: 'integer',
		description: 'Колонка usergroups.admin_rss',
	)]
	public int $admin_rss = 0;
	#[OA\Property(
		property: 'admin_iptools',
		type: 'integer',
		description: 'Колонка usergroups.admin_iptools',
	)]
	public int $admin_iptools = 0;
	#[OA\Property(
		property: 'admin_rssinform',
		type: 'integer',
		description: 'Колонка usergroups.admin_rssinform',
	)]
	public int $admin_rssinform = 0;
	#[OA\Property(
		property: 'admin_googlemap',
		type: 'integer',
		description: 'Колонка usergroups.admin_googlemap',
	)]
	public int $admin_googlemap = 0;
	#[OA\Property(
		property: 'allow_html',
		type: 'integer',
		description: 'Колонка usergroups.allow_html',
	)]
	public int $allow_html = 1;
	#[OA\Property(
		property: 'group_prefix',
		type: 'string',
		description: 'Колонка usergroups.group_prefix',
	)]
	public string $group_prefix = '';
	#[OA\Property(
		property: 'group_suffix',
		type: 'string',
		description: 'Колонка usergroups.group_suffix',
	)]
	public string $group_suffix = '';
	#[OA\Property(
		property: 'allow_subscribe',
		type: 'integer',
		description: 'Колонка usergroups.allow_subscribe',
	)]
	public int $allow_subscribe = 0;
	#[OA\Property(
		property: 'allow_image_size',
		type: 'integer',
		description: 'Колонка usergroups.allow_image_size',
	)]
	public int $allow_image_size = 0;
	#[OA\Property(
		property: 'cat_allow_addnews',
		type: 'string',
		description: 'Колонка usergroups.cat_allow_addnews',
	)]
	public string $cat_allow_addnews = '';
	#[OA\Property(
		property: 'flood_news',
		type: 'integer',
		description: 'Колонка usergroups.flood_news',
	)]
	public int $flood_news = 0;
	#[OA\Property(
		property: 'max_day_news',
		type: 'integer',
		description: 'Колонка usergroups.max_day_news',
	)]
	public int $max_day_news = 0;
	#[OA\Property(
		property: 'force_leech',
		type: 'integer',
		description: 'Колонка usergroups.force_leech',
	)]
	public int $force_leech = 0;
	#[OA\Property(
		property: 'edit_limit',
		type: 'integer',
		description: 'Колонка usergroups.edit_limit',
	)]
	public int $edit_limit = 0;
	#[OA\Property(
		property: 'captcha_pm',
		type: 'integer',
		description: 'Колонка usergroups.captcha_pm',
	)]
	public int $captcha_pm = 0;
	#[OA\Property(
		property: 'max_pm_day',
		type: 'integer',
		description: 'Колонка usergroups.max_pm_day',
	)]
	public int $max_pm_day = 0;
	#[OA\Property(
		property: 'max_mail_day',
		type: 'integer',
		description: 'Колонка usergroups.max_mail_day',
	)]
	public int $max_mail_day = 0;
	#[OA\Property(
		property: 'admin_tagscloud',
		type: 'integer',
		description: 'Колонка usergroups.admin_tagscloud',
	)]
	public int $admin_tagscloud = 0;
	#[OA\Property(
		property: 'allow_vote',
		type: 'integer',
		description: 'Колонка usergroups.allow_vote',
	)]
	public int $allow_vote = 0;
	#[OA\Property(
		property: 'admin_complaint',
		type: 'integer',
		description: 'Колонка usergroups.admin_complaint',
	)]
	public int $admin_complaint = 0;
	#[OA\Property(
		property: 'news_question',
		type: 'integer',
		description: 'Колонка usergroups.news_question',
	)]
	public int $news_question = 0;
	#[OA\Property(
		property: 'comments_question',
		type: 'integer',
		description: 'Колонка usergroups.comments_question',
	)]
	public int $comments_question = 0;
	#[OA\Property(
		property: 'max_comment_day',
		type: 'integer',
		description: 'Колонка usergroups.max_comment_day',
	)]
	public int $max_comment_day = 0;
	#[OA\Property(
		property: 'max_images',
		type: 'integer',
		description: 'Колонка usergroups.max_images',
	)]
	public int $max_images = 0;
	#[OA\Property(
		property: 'max_files',
		type: 'integer',
		description: 'Колонка usergroups.max_files',
	)]
	public int $max_files = 0;
	#[OA\Property(
		property: 'disable_news_captcha',
		type: 'integer',
		description: 'Колонка usergroups.disable_news_captcha',
	)]
	public int $disable_news_captcha = 0;
	#[OA\Property(
		property: 'disable_comments_captcha',
		type: 'integer',
		description: 'Колонка usergroups.disable_comments_captcha',
	)]
	public int $disable_comments_captcha = 0;
	#[OA\Property(
		property: 'pm_question',
		type: 'integer',
		description: 'Колонка usergroups.pm_question',
	)]
	public int $pm_question = 0;
	#[OA\Property(
		property: 'captcha_feedback',
		type: 'integer',
		description: 'Колонка usergroups.captcha_feedback',
	)]
	public int $captcha_feedback = 1;
	#[OA\Property(
		property: 'feedback_question',
		type: 'integer',
		description: 'Колонка usergroups.feedback_question',
	)]
	public int $feedback_question = 0;
	#[OA\Property(
		property: 'files_type',
		type: 'string',
		description: 'Колонка usergroups.files_type',
	)]
	public string $files_type = '';
	#[OA\Property(
		property: 'max_file_size',
		type: 'integer',
		description: 'Колонка usergroups.max_file_size',
	)]
	public int $max_file_size = 0;
	#[OA\Property(
		property: 'files_max_speed',
		type: 'integer',
		description: 'Колонка usergroups.files_max_speed',
	)]
	public int $files_max_speed = 0;
	#[OA\Property(
		property: 'spamfilter',
		type: 'integer',
		description: 'Колонка usergroups.spamfilter',
	)]
	public int $spamfilter = 2;
	#[OA\Property(
		property: 'allow_comments_rating',
		type: 'integer',
		description: 'Колонка usergroups.allow_comments_rating',
	)]
	public int $allow_comments_rating = 1;
	#[OA\Property(
		property: 'max_edit_days',
		type: 'integer',
		description: 'Колонка usergroups.max_edit_days',
	)]
	public int $max_edit_days = 0;
	#[OA\Property(
		property: 'spampmfilter',
		type: 'integer',
		description: 'Колонка usergroups.spampmfilter',
	)]
	public int $spampmfilter = 0;
	#[OA\Property(
		property: 'force_reg',
		type: 'integer',
		description: 'Колонка usergroups.force_reg',
	)]
	public int $force_reg = 0;
	#[OA\Property(
		property: 'force_reg_days',
		type: 'integer',
		description: 'Колонка usergroups.force_reg_days',
	)]
	public int $force_reg_days = 0;
	#[OA\Property(
		property: 'force_reg_group',
		type: 'integer',
		description: 'Колонка usergroups.force_reg_group',
	)]
	public int $force_reg_group = 4;
	#[OA\Property(
		property: 'force_news',
		type: 'integer',
		description: 'Колонка usergroups.force_news',
	)]
	public int $force_news = 0;
	#[OA\Property(
		property: 'force_news_count',
		type: 'integer',
		description: 'Колонка usergroups.force_news_count',
	)]
	public int $force_news_count = 0;
	#[OA\Property(
		property: 'force_news_group',
		type: 'integer',
		description: 'Колонка usergroups.force_news_group',
	)]
	public int $force_news_group = 4;
	#[OA\Property(
		property: 'force_comments',
		type: 'integer',
		description: 'Колонка usergroups.force_comments',
	)]
	public int $force_comments = 0;
	#[OA\Property(
		property: 'force_comments_count',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_count',
	)]
	public int $force_comments_count = 0;
	#[OA\Property(
		property: 'force_comments_group',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_group',
	)]
	public int $force_comments_group = 4;
	#[OA\Property(
		property: 'force_rating',
		type: 'integer',
		description: 'Колонка usergroups.force_rating',
	)]
	public int $force_rating = 0;
	#[OA\Property(
		property: 'force_rating_count',
		type: 'integer',
		description: 'Колонка usergroups.force_rating_count',
	)]
	public int $force_rating_count = 0;
	#[OA\Property(
		property: 'force_rating_group',
		type: 'integer',
		description: 'Колонка usergroups.force_rating_group',
	)]
	public int $force_rating_group = 4;
	#[OA\Property(
		property: 'not_allow_cats',
		type: 'string',
		description: 'Колонка usergroups.not_allow_cats',
	)]
	public string $not_allow_cats = '';
	#[OA\Property(
		property: 'allow_up_image',
		type: 'integer',
		description: 'Колонка usergroups.allow_up_image',
	)]
	public int $allow_up_image = 0;
	#[OA\Property(
		property: 'allow_up_watermark',
		type: 'integer',
		description: 'Колонка usergroups.allow_up_watermark',
	)]
	public int $allow_up_watermark = 0;
	#[OA\Property(
		property: 'allow_up_thumb',
		type: 'integer',
		description: 'Колонка usergroups.allow_up_thumb',
	)]
	public int $allow_up_thumb = 0;
	#[OA\Property(
		property: 'up_count_image',
		type: 'integer',
		description: 'Колонка usergroups.up_count_image',
	)]
	public int $up_count_image = 0;
	#[OA\Property(
		property: 'up_image_side',
		type: 'string',
		description: 'Колонка usergroups.up_image_side',
	)]
	public string $up_image_side = '';
	#[OA\Property(
		property: 'up_image_size',
		type: 'integer',
		description: 'Колонка usergroups.up_image_size',
	)]
	public int $up_image_size = 0;
	#[OA\Property(
		property: 'up_thumb_size',
		type: 'string',
		description: 'Колонка usergroups.up_thumb_size',
	)]
	public string $up_thumb_size = '';
	#[OA\Property(
		property: 'allow_mail_files',
		type: 'integer',
		description: 'Колонка usergroups.allow_mail_files',
	)]
	public int $allow_mail_files = 0;
	#[OA\Property(
		property: 'max_mail_files',
		type: 'integer',
		description: 'Колонка usergroups.max_mail_files',
	)]
	public int $max_mail_files = 0;
	#[OA\Property(
		property: 'max_mail_allfiles',
		type: 'integer',
		description: 'Колонка usergroups.max_mail_allfiles',
	)]
	public int $max_mail_allfiles = 0;
	#[OA\Property(
		property: 'mail_files_type',
		type: 'string',
		description: 'Колонка usergroups.mail_files_type',
	)]
	public string $mail_files_type = '';
	#[OA\Property(
		property: 'video_comments',
		type: 'integer',
		description: 'Колонка usergroups.video_comments',
	)]
	public int $video_comments = 0;
	#[OA\Property(
		property: 'media_comments',
		type: 'integer',
		description: 'Колонка usergroups.media_comments',
	)]
	public int $media_comments = 0;
	#[OA\Property(
		property: 'min_image_side',
		type: 'string',
		description: 'Колонка usergroups.min_image_side',
	)]
	public string $min_image_side = '';
	#[OA\Property(
		property: 'allow_public_file_upload',
		type: 'integer',
		description: 'Колонка usergroups.allow_public_file_upload',
	)]
	public int $allow_public_file_upload = 0;
	#[OA\Property(
		property: 'force_comments_rating',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_rating',
	)]
	public int $force_comments_rating = 0;
	#[OA\Property(
		property: 'force_comments_rating_count',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_rating_count',
	)]
	public int $force_comments_rating_count = 0;
	#[OA\Property(
		property: 'force_comments_rating_group',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_rating_group',
	)]
	public int $force_comments_rating_group = 0;
	#[OA\Property(
		property: 'max_downloads',
		type: 'integer',
		description: 'Колонка usergroups.max_downloads',
	)]
	public int $max_downloads = 0;
	#[OA\Property(
		property: 'admin_links',
		type: 'integer',
		description: 'Колонка usergroups.admin_links',
	)]
	public int $admin_links = 0;
	#[OA\Property(
		property: 'admin_meta',
		type: 'integer',
		description: 'Колонка usergroups.admin_meta',
	)]
	public int $admin_meta = 0;
	#[OA\Property(
		property: 'admin_redirects',
		type: 'integer',
		description: 'Колонка usergroups.admin_redirects',
	)]
	public int $admin_redirects = 0;
	#[OA\Property(
		property: 'allow_change_storage',
		type: 'integer',
		description: 'Колонка usergroups.allow_change_storage',
	)]
	public int $allow_change_storage = 0;
	#[OA\Property(
		property: 'self_delete',
		type: 'integer',
		description: 'Колонка usergroups.self_delete',
	)]
	public int $self_delete = 2;
	#[OA\Property(
		property: 'allow_complaint_news',
		type: 'integer',
		description: 'Колонка usergroups.allow_complaint_news',
	)]
	public int $allow_complaint_news = 1;
	#[OA\Property(
		property: 'allow_complaint_comments',
		type: 'integer',
		description: 'Колонка usergroups.allow_complaint_comments',
	)]
	public int $allow_complaint_comments = 1;
	#[OA\Property(
		property: 'allow_complaint_orfo',
		type: 'integer',
		description: 'Колонка usergroups.allow_complaint_orfo',
	)]
	public int $allow_complaint_orfo = 1;
	#[OA\Property(
		property: 'flood_time',
		type: 'integer',
		description: 'Колонка usergroups.flood_time',
	)]
	public int $flood_time = 0;
	#[OA\Property(
		property: 'max_c_negative',
		type: 'integer',
		description: 'Колонка usergroups.max_c_negative',
	)]
	public int $max_c_negative = 0;
	#[OA\Property(
		property: 'max_n_negative',
		type: 'integer',
		description: 'Колонка usergroups.max_n_negative',
	)]
	public int $max_n_negative = 0;
	#[OA\Property(
		property: 'rating_n_day',
		type: 'integer',
		description: 'Колонка usergroups.rating_n_day',
	)]
	public int $rating_n_day = 0;
	#[OA\Property(
		property: 'rating_c_day',
		type: 'integer',
		description: 'Колонка usergroups.rating_c_day',
	)]
	public int $rating_c_day = 0;
	#[OA\Property(
		property: 'allow_rating_change',
		type: 'integer',
		description: 'Колонка usergroups.allow_rating_change',
	)]
	public int $allow_rating_change = 1;
	#[OA\Property(
		property: 'allow_crating_change',
		type: 'integer',
		description: 'Колонка usergroups.allow_crating_change',
	)]
	public int $allow_crating_change = 1;

	public function table(): string {
		return 'usergroups';
	}

	protected function columnList(): array {
		return [
			'id',
			'group_name',
			'allow_cats',
			'allow_adds',
			'cat_add',
			'allow_admin',
			'allow_addc',
			'allow_editc',
			'allow_delc',
			'edit_allc',
			'del_allc',
			'moderation',
			'allow_all_edit',
			'allow_edit',
			'allow_pm',
			'max_pm',
			'max_foto',
			'allow_files',
			'allow_hide',
			'allow_short',
			'time_limit',
			'rid',
			'allow_fixed',
			'allow_feed',
			'allow_search',
			'allow_poll',
			'allow_main',
			'captcha',
			'icon',
			'allow_modc',
			'allow_rating',
			'allow_offline',
			'allow_image_upload',
			'allow_file_upload',
			'allow_signature',
			'allow_url',
			'news_sec_code',
			'allow_image',
			'max_signature',
			'max_info',
			'admin_addnews',
			'admin_editnews',
			'admin_comments',
			'admin_categories',
			'admin_editusers',
			'admin_wordfilter',
			'admin_xfields',
			'admin_userfields',
			'admin_static',
			'admin_editvote',
			'admin_newsletter',
			'admin_blockip',
			'admin_banners',
			'admin_rss',
			'admin_iptools',
			'admin_rssinform',
			'admin_googlemap',
			'allow_html',
			'group_prefix',
			'group_suffix',
			'allow_subscribe',
			'allow_image_size',
			'cat_allow_addnews',
			'flood_news',
			'max_day_news',
			'force_leech',
			'edit_limit',
			'captcha_pm',
			'max_pm_day',
			'max_mail_day',
			'admin_tagscloud',
			'allow_vote',
			'admin_complaint',
			'news_question',
			'comments_question',
			'max_comment_day',
			'max_images',
			'max_files',
			'disable_news_captcha',
			'disable_comments_captcha',
			'pm_question',
			'captcha_feedback',
			'feedback_question',
			'files_type',
			'max_file_size',
			'files_max_speed',
			'spamfilter',
			'allow_comments_rating',
			'max_edit_days',
			'spampmfilter',
			'force_reg',
			'force_reg_days',
			'force_reg_group',
			'force_news',
			'force_news_count',
			'force_news_group',
			'force_comments',
			'force_comments_count',
			'force_comments_group',
			'force_rating',
			'force_rating_count',
			'force_rating_group',
			'not_allow_cats',
			'allow_up_image',
			'allow_up_watermark',
			'allow_up_thumb',
			'up_count_image',
			'up_image_side',
			'up_image_size',
			'up_thumb_size',
			'allow_mail_files',
			'max_mail_files',
			'max_mail_allfiles',
			'mail_files_type',
			'video_comments',
			'media_comments',
			'min_image_side',
			'allow_public_file_upload',
			'force_comments_rating',
			'force_comments_rating_count',
			'force_comments_rating_group',
			'max_downloads',
			'admin_links',
			'admin_meta',
			'admin_redirects',
			'allow_change_storage',
			'self_delete',
			'allow_complaint_news',
			'allow_complaint_comments',
			'allow_complaint_orfo',
			'flood_time',
			'max_c_negative',
			'max_n_negative',
			'rating_n_day',
			'rating_c_day',
			'allow_rating_change',
			'allow_crating_change',
		];
	}

	protected function defaultMap(): array {
		return [
			'group_name' => '',
			'allow_cats' => '',
			'allow_adds' => 1,
			'cat_add' => '',
			'allow_admin' => 0,
			'allow_addc' => 0,
			'allow_editc' => 0,
			'allow_delc' => 0,
			'edit_allc' => 0,
			'del_allc' => 0,
			'moderation' => 0,
			'allow_all_edit' => 0,
			'allow_edit' => 0,
			'allow_pm' => 0,
			'max_pm' => 0,
			'max_foto' => '',
			'allow_files' => 0,
			'allow_hide' => 1,
			'allow_short' => 0,
			'time_limit' => 0,
			'rid' => 0,
			'allow_fixed' => 0,
			'allow_feed' => 1,
			'allow_search' => 1,
			'allow_poll' => 1,
			'allow_main' => 1,
			'captcha' => 0,
			'icon' => '',
			'allow_modc' => 0,
			'allow_rating' => 1,
			'allow_offline' => 0,
			'allow_image_upload' => 0,
			'allow_file_upload' => 0,
			'allow_signature' => 0,
			'allow_url' => 1,
			'news_sec_code' => 1,
			'allow_image' => 0,
			'max_signature' => 0,
			'max_info' => 0,
			'admin_addnews' => 0,
			'admin_editnews' => 0,
			'admin_comments' => 0,
			'admin_categories' => 0,
			'admin_editusers' => 0,
			'admin_wordfilter' => 0,
			'admin_xfields' => 0,
			'admin_userfields' => 0,
			'admin_static' => 0,
			'admin_editvote' => 0,
			'admin_newsletter' => 0,
			'admin_blockip' => 0,
			'admin_banners' => 0,
			'admin_rss' => 0,
			'admin_iptools' => 0,
			'admin_rssinform' => 0,
			'admin_googlemap' => 0,
			'allow_html' => 1,
			'group_prefix' => '',
			'group_suffix' => '',
			'allow_subscribe' => 0,
			'allow_image_size' => 0,
			'cat_allow_addnews' => '',
			'flood_news' => 0,
			'max_day_news' => 0,
			'force_leech' => 0,
			'edit_limit' => 0,
			'captcha_pm' => 0,
			'max_pm_day' => 0,
			'max_mail_day' => 0,
			'admin_tagscloud' => 0,
			'allow_vote' => 0,
			'admin_complaint' => 0,
			'news_question' => 0,
			'comments_question' => 0,
			'max_comment_day' => 0,
			'max_images' => 0,
			'max_files' => 0,
			'disable_news_captcha' => 0,
			'disable_comments_captcha' => 0,
			'pm_question' => 0,
			'captcha_feedback' => 1,
			'feedback_question' => 0,
			'files_type' => '',
			'max_file_size' => 0,
			'files_max_speed' => 0,
			'spamfilter' => 2,
			'allow_comments_rating' => 1,
			'max_edit_days' => 0,
			'spampmfilter' => 0,
			'force_reg' => 0,
			'force_reg_days' => 0,
			'force_reg_group' => 4,
			'force_news' => 0,
			'force_news_count' => 0,
			'force_news_group' => 4,
			'force_comments' => 0,
			'force_comments_count' => 0,
			'force_comments_group' => 4,
			'force_rating' => 0,
			'force_rating_count' => 0,
			'force_rating_group' => 4,
			'not_allow_cats' => '',
			'allow_up_image' => 0,
			'allow_up_watermark' => 0,
			'allow_up_thumb' => 0,
			'up_count_image' => 0,
			'up_image_side' => '',
			'up_image_size' => 0,
			'up_thumb_size' => '',
			'allow_mail_files' => 0,
			'max_mail_files' => 0,
			'max_mail_allfiles' => 0,
			'mail_files_type' => '',
			'video_comments' => 0,
			'media_comments' => 0,
			'min_image_side' => '',
			'allow_public_file_upload' => 0,
			'force_comments_rating' => 0,
			'force_comments_rating_count' => 0,
			'force_comments_rating_group' => 0,
			'max_downloads' => 0,
			'admin_links' => 0,
			'admin_meta' => 0,
			'admin_redirects' => 0,
			'allow_change_storage' => 0,
			'self_delete' => 2,
			'allow_complaint_news' => 1,
			'allow_complaint_comments' => 1,
			'allow_complaint_orfo' => 1,
			'flood_time' => 0,
			'max_c_negative' => 0,
			'max_n_negative' => 0,
			'rating_n_day' => 0,
			'rating_c_day' => 0,
			'allow_rating_change' => 1,
			'allow_crating_change' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
|||||||
=======
<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Схема таблицы `usergroups`.
 */
#[OA\Schema(schema: 'Usergroups')]
final class UsergroupsSchema extends AbstractTableSchema {
	#[OA\Property(
		property: 'id',
		type: 'integer',
		description: 'Первичный ключ (usergroups.id)',
	)]
	public int $id = 0;
	#[OA\Property(
		property: 'group_name',
		type: 'string',
		description: 'Колонка usergroups.group_name',
	)]
	public string $group_name = '';
	#[OA\Property(
		property: 'allow_cats',
		type: 'string',
		description: 'Колонка usergroups.allow_cats',
	)]
	public string $allow_cats = '';
	#[OA\Property(
		property: 'allow_adds',
		type: 'integer',
		description: 'Колонка usergroups.allow_adds',
	)]
	public int $allow_adds = 1;
	#[OA\Property(
		property: 'cat_add',
		type: 'string',
		description: 'Колонка usergroups.cat_add',
	)]
	public string $cat_add = '';
	#[OA\Property(
		property: 'allow_admin',
		type: 'integer',
		description: 'Колонка usergroups.allow_admin',
	)]
	public int $allow_admin = 0;
	#[OA\Property(
		property: 'allow_addc',
		type: 'integer',
		description: 'Колонка usergroups.allow_addc',
	)]
	public int $allow_addc = 0;
	#[OA\Property(
		property: 'allow_editc',
		type: 'integer',
		description: 'Колонка usergroups.allow_editc',
	)]
	public int $allow_editc = 0;
	#[OA\Property(
		property: 'allow_delc',
		type: 'integer',
		description: 'Колонка usergroups.allow_delc',
	)]
	public int $allow_delc = 0;
	#[OA\Property(
		property: 'edit_allc',
		type: 'integer',
		description: 'Колонка usergroups.edit_allc',
	)]
	public int $edit_allc = 0;
	#[OA\Property(
		property: 'del_allc',
		type: 'integer',
		description: 'Колонка usergroups.del_allc',
	)]
	public int $del_allc = 0;
	#[OA\Property(
		property: 'moderation',
		type: 'integer',
		description: 'Колонка usergroups.moderation',
	)]
	public int $moderation = 0;
	#[OA\Property(
		property: 'allow_all_edit',
		type: 'integer',
		description: 'Колонка usergroups.allow_all_edit',
	)]
	public int $allow_all_edit = 0;
	#[OA\Property(
		property: 'allow_edit',
		type: 'integer',
		description: 'Колонка usergroups.allow_edit',
	)]
	public int $allow_edit = 0;
	#[OA\Property(
		property: 'allow_pm',
		type: 'integer',
		description: 'Колонка usergroups.allow_pm',
	)]
	public int $allow_pm = 0;
	#[OA\Property(
		property: 'max_pm',
		type: 'integer',
		description: 'Колонка usergroups.max_pm',
	)]
	public int $max_pm = 0;
	#[OA\Property(
		property: 'max_foto',
		type: 'string',
		description: 'Колонка usergroups.max_foto',
	)]
	public string $max_foto = '';
	#[OA\Property(
		property: 'allow_files',
		type: 'integer',
		description: 'Колонка usergroups.allow_files',
	)]
	public int $allow_files = 0;
	#[OA\Property(
		property: 'allow_hide',
		type: 'integer',
		description: 'Колонка usergroups.allow_hide',
	)]
	public int $allow_hide = 1;
	#[OA\Property(
		property: 'allow_short',
		type: 'integer',
		description: 'Колонка usergroups.allow_short',
	)]
	public int $allow_short = 0;
	#[OA\Property(
		property: 'time_limit',
		type: 'integer',
		description: 'Колонка usergroups.time_limit',
	)]
	public int $time_limit = 0;
	#[OA\Property(
		property: 'rid',
		type: 'integer',
		description: 'Колонка usergroups.rid',
	)]
	public int $rid = 0;
	#[OA\Property(
		property: 'allow_fixed',
		type: 'integer',
		description: 'Колонка usergroups.allow_fixed',
	)]
	public int $allow_fixed = 0;
	#[OA\Property(
		property: 'allow_feed',
		type: 'integer',
		description: 'Колонка usergroups.allow_feed',
	)]
	public int $allow_feed = 1;
	#[OA\Property(
		property: 'allow_search',
		type: 'integer',
		description: 'Колонка usergroups.allow_search',
	)]
	public int $allow_search = 1;
	#[OA\Property(
		property: 'allow_poll',
		type: 'integer',
		description: 'Колонка usergroups.allow_poll',
	)]
	public int $allow_poll = 1;
	#[OA\Property(
		property: 'allow_main',
		type: 'integer',
		description: 'Колонка usergroups.allow_main',
	)]
	public int $allow_main = 1;
	#[OA\Property(
		property: 'captcha',
		type: 'integer',
		description: 'Колонка usergroups.captcha',
	)]
	public int $captcha = 0;
	#[OA\Property(
		property: 'icon',
		type: 'string',
		description: 'Колонка usergroups.icon',
	)]
	public string $icon = '';
	#[OA\Property(
		property: 'allow_modc',
		type: 'integer',
		description: 'Колонка usergroups.allow_modc',
	)]
	public int $allow_modc = 0;
	#[OA\Property(
		property: 'allow_rating',
		type: 'integer',
		description: 'Колонка usergroups.allow_rating',
	)]
	public int $allow_rating = 1;
	#[OA\Property(
		property: 'allow_offline',
		type: 'integer',
		description: 'Колонка usergroups.allow_offline',
	)]
	public int $allow_offline = 0;
	#[OA\Property(
		property: 'allow_image_upload',
		type: 'integer',
		description: 'Колонка usergroups.allow_image_upload',
	)]
	public int $allow_image_upload = 0;
	#[OA\Property(
		property: 'allow_file_upload',
		type: 'integer',
		description: 'Колонка usergroups.allow_file_upload',
	)]
	public int $allow_file_upload = 0;
	#[OA\Property(
		property: 'allow_signature',
		type: 'integer',
		description: 'Колонка usergroups.allow_signature',
	)]
	public int $allow_signature = 0;
	#[OA\Property(
		property: 'allow_url',
		type: 'integer',
		description: 'Колонка usergroups.allow_url',
	)]
	public int $allow_url = 1;
	#[OA\Property(
		property: 'news_sec_code',
		type: 'integer',
		description: 'Колонка usergroups.news_sec_code',
	)]
	public int $news_sec_code = 1;
	#[OA\Property(
		property: 'allow_image',
		type: 'integer',
		description: 'Колонка usergroups.allow_image',
	)]
	public int $allow_image = 0;
	#[OA\Property(
		property: 'max_signature',
		type: 'integer',
		description: 'Колонка usergroups.max_signature',
	)]
	public int $max_signature = 0;
	#[OA\Property(
		property: 'max_info',
		type: 'integer',
		description: 'Колонка usergroups.max_info',
	)]
	public int $max_info = 0;
	#[OA\Property(
		property: 'admin_addnews',
		type: 'integer',
		description: 'Колонка usergroups.admin_addnews',
	)]
	public int $admin_addnews = 0;
	#[OA\Property(
		property: 'admin_editnews',
		type: 'integer',
		description: 'Колонка usergroups.admin_editnews',
	)]
	public int $admin_editnews = 0;
	#[OA\Property(
		property: 'admin_comments',
		type: 'integer',
		description: 'Колонка usergroups.admin_comments',
	)]
	public int $admin_comments = 0;
	#[OA\Property(
		property: 'admin_categories',
		type: 'integer',
		description: 'Колонка usergroups.admin_categories',
	)]
	public int $admin_categories = 0;
	#[OA\Property(
		property: 'admin_editusers',
		type: 'integer',
		description: 'Колонка usergroups.admin_editusers',
	)]
	public int $admin_editusers = 0;
	#[OA\Property(
		property: 'admin_wordfilter',
		type: 'integer',
		description: 'Колонка usergroups.admin_wordfilter',
	)]
	public int $admin_wordfilter = 0;
	#[OA\Property(
		property: 'admin_xfields',
		type: 'integer',
		description: 'Колонка usergroups.admin_xfields',
	)]
	public int $admin_xfields = 0;
	#[OA\Property(
		property: 'admin_userfields',
		type: 'integer',
		description: 'Колонка usergroups.admin_userfields',
	)]
	public int $admin_userfields = 0;
	#[OA\Property(
		property: 'admin_static',
		type: 'integer',
		description: 'Колонка usergroups.admin_static',
	)]
	public int $admin_static = 0;
	#[OA\Property(
		property: 'admin_editvote',
		type: 'integer',
		description: 'Колонка usergroups.admin_editvote',
	)]
	public int $admin_editvote = 0;
	#[OA\Property(
		property: 'admin_newsletter',
		type: 'integer',
		description: 'Колонка usergroups.admin_newsletter',
	)]
	public int $admin_newsletter = 0;
	#[OA\Property(
		property: 'admin_blockip',
		type: 'integer',
		description: 'Колонка usergroups.admin_blockip',
	)]
	public int $admin_blockip = 0;
	#[OA\Property(
		property: 'admin_banners',
		type: 'integer',
		description: 'Колонка usergroups.admin_banners',
	)]
	public int $admin_banners = 0;
	#[OA\Property(
		property: 'admin_rss',
		type: 'integer',
		description: 'Колонка usergroups.admin_rss',
	)]
	public int $admin_rss = 0;
	#[OA\Property(
		property: 'admin_iptools',
		type: 'integer',
		description: 'Колонка usergroups.admin_iptools',
	)]
	public int $admin_iptools = 0;
	#[OA\Property(
		property: 'admin_rssinform',
		type: 'integer',
		description: 'Колонка usergroups.admin_rssinform',
	)]
	public int $admin_rssinform = 0;
	#[OA\Property(
		property: 'admin_googlemap',
		type: 'integer',
		description: 'Колонка usergroups.admin_googlemap',
	)]
	public int $admin_googlemap = 0;
	#[OA\Property(
		property: 'allow_html',
		type: 'integer',
		description: 'Колонка usergroups.allow_html',
	)]
	public int $allow_html = 1;
	#[OA\Property(
		property: 'group_prefix',
		type: 'string',
		description: 'Колонка usergroups.group_prefix',
	)]
	public string $group_prefix = '';
	#[OA\Property(
		property: 'group_suffix',
		type: 'string',
		description: 'Колонка usergroups.group_suffix',
	)]
	public string $group_suffix = '';
	#[OA\Property(
		property: 'allow_subscribe',
		type: 'integer',
		description: 'Колонка usergroups.allow_subscribe',
	)]
	public int $allow_subscribe = 0;
	#[OA\Property(
		property: 'allow_image_size',
		type: 'integer',
		description: 'Колонка usergroups.allow_image_size',
	)]
	public int $allow_image_size = 0;
	#[OA\Property(
		property: 'cat_allow_addnews',
		type: 'string',
		description: 'Колонка usergroups.cat_allow_addnews',
	)]
	public string $cat_allow_addnews = '';
	#[OA\Property(
		property: 'flood_news',
		type: 'integer',
		description: 'Колонка usergroups.flood_news',
	)]
	public int $flood_news = 0;
	#[OA\Property(
		property: 'max_day_news',
		type: 'integer',
		description: 'Колонка usergroups.max_day_news',
	)]
	public int $max_day_news = 0;
	#[OA\Property(
		property: 'force_leech',
		type: 'integer',
		description: 'Колонка usergroups.force_leech',
	)]
	public int $force_leech = 0;
	#[OA\Property(
		property: 'edit_limit',
		type: 'integer',
		description: 'Колонка usergroups.edit_limit',
	)]
	public int $edit_limit = 0;
	#[OA\Property(
		property: 'captcha_pm',
		type: 'integer',
		description: 'Колонка usergroups.captcha_pm',
	)]
	public int $captcha_pm = 0;
	#[OA\Property(
		property: 'max_pm_day',
		type: 'integer',
		description: 'Колонка usergroups.max_pm_day',
	)]
	public int $max_pm_day = 0;
	#[OA\Property(
		property: 'max_mail_day',
		type: 'integer',
		description: 'Колонка usergroups.max_mail_day',
	)]
	public int $max_mail_day = 0;
	#[OA\Property(
		property: 'admin_tagscloud',
		type: 'integer',
		description: 'Колонка usergroups.admin_tagscloud',
	)]
	public int $admin_tagscloud = 0;
	#[OA\Property(
		property: 'allow_vote',
		type: 'integer',
		description: 'Колонка usergroups.allow_vote',
	)]
	public int $allow_vote = 0;
	#[OA\Property(
		property: 'admin_complaint',
		type: 'integer',
		description: 'Колонка usergroups.admin_complaint',
	)]
	public int $admin_complaint = 0;
	#[OA\Property(
		property: 'news_question',
		type: 'integer',
		description: 'Колонка usergroups.news_question',
	)]
	public int $news_question = 0;
	#[OA\Property(
		property: 'comments_question',
		type: 'integer',
		description: 'Колонка usergroups.comments_question',
	)]
	public int $comments_question = 0;
	#[OA\Property(
		property: 'max_comment_day',
		type: 'integer',
		description: 'Колонка usergroups.max_comment_day',
	)]
	public int $max_comment_day = 0;
	#[OA\Property(
		property: 'max_images',
		type: 'integer',
		description: 'Колонка usergroups.max_images',
	)]
	public int $max_images = 0;
	#[OA\Property(
		property: 'max_files',
		type: 'integer',
		description: 'Колонка usergroups.max_files',
	)]
	public int $max_files = 0;
	#[OA\Property(
		property: 'disable_news_captcha',
		type: 'integer',
		description: 'Колонка usergroups.disable_news_captcha',
	)]
	public int $disable_news_captcha = 0;
	#[OA\Property(
		property: 'disable_comments_captcha',
		type: 'integer',
		description: 'Колонка usergroups.disable_comments_captcha',
	)]
	public int $disable_comments_captcha = 0;
	#[OA\Property(
		property: 'pm_question',
		type: 'integer',
		description: 'Колонка usergroups.pm_question',
	)]
	public int $pm_question = 0;
	#[OA\Property(
		property: 'captcha_feedback',
		type: 'integer',
		description: 'Колонка usergroups.captcha_feedback',
	)]
	public int $captcha_feedback = 1;
	#[OA\Property(
		property: 'feedback_question',
		type: 'integer',
		description: 'Колонка usergroups.feedback_question',
	)]
	public int $feedback_question = 0;
	#[OA\Property(
		property: 'files_type',
		type: 'string',
		description: 'Колонка usergroups.files_type',
	)]
	public string $files_type = '';
	#[OA\Property(
		property: 'max_file_size',
		type: 'integer',
		description: 'Колонка usergroups.max_file_size',
	)]
	public int $max_file_size = 0;
	#[OA\Property(
		property: 'files_max_speed',
		type: 'integer',
		description: 'Колонка usergroups.files_max_speed',
	)]
	public int $files_max_speed = 0;
	#[OA\Property(
		property: 'spamfilter',
		type: 'integer',
		description: 'Колонка usergroups.spamfilter',
	)]
	public int $spamfilter = 2;
	#[OA\Property(
		property: 'allow_comments_rating',
		type: 'integer',
		description: 'Колонка usergroups.allow_comments_rating',
	)]
	public int $allow_comments_rating = 1;
	#[OA\Property(
		property: 'max_edit_days',
		type: 'integer',
		description: 'Колонка usergroups.max_edit_days',
	)]
	public int $max_edit_days = 0;
	#[OA\Property(
		property: 'spampmfilter',
		type: 'integer',
		description: 'Колонка usergroups.spampmfilter',
	)]
	public int $spampmfilter = 0;
	#[OA\Property(
		property: 'force_reg',
		type: 'integer',
		description: 'Колонка usergroups.force_reg',
	)]
	public int $force_reg = 0;
	#[OA\Property(
		property: 'force_reg_days',
		type: 'integer',
		description: 'Колонка usergroups.force_reg_days',
	)]
	public int $force_reg_days = 0;
	#[OA\Property(
		property: 'force_reg_group',
		type: 'integer',
		description: 'Колонка usergroups.force_reg_group',
	)]
	public int $force_reg_group = 4;
	#[OA\Property(
		property: 'force_news',
		type: 'integer',
		description: 'Колонка usergroups.force_news',
	)]
	public int $force_news = 0;
	#[OA\Property(
		property: 'force_news_count',
		type: 'integer',
		description: 'Колонка usergroups.force_news_count',
	)]
	public int $force_news_count = 0;
	#[OA\Property(
		property: 'force_news_group',
		type: 'integer',
		description: 'Колонка usergroups.force_news_group',
	)]
	public int $force_news_group = 4;
	#[OA\Property(
		property: 'force_comments',
		type: 'integer',
		description: 'Колонка usergroups.force_comments',
	)]
	public int $force_comments = 0;
	#[OA\Property(
		property: 'force_comments_count',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_count',
	)]
	public int $force_comments_count = 0;
	#[OA\Property(
		property: 'force_comments_group',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_group',
	)]
	public int $force_comments_group = 4;
	#[OA\Property(
		property: 'force_rating',
		type: 'integer',
		description: 'Колонка usergroups.force_rating',
	)]
	public int $force_rating = 0;
	#[OA\Property(
		property: 'force_rating_count',
		type: 'integer',
		description: 'Колонка usergroups.force_rating_count',
	)]
	public int $force_rating_count = 0;
	#[OA\Property(
		property: 'force_rating_group',
		type: 'integer',
		description: 'Колонка usergroups.force_rating_group',
	)]
	public int $force_rating_group = 4;
	#[OA\Property(
		property: 'not_allow_cats',
		type: 'string',
		description: 'Колонка usergroups.not_allow_cats',
	)]
	public string $not_allow_cats = '';
	#[OA\Property(
		property: 'allow_up_image',
		type: 'integer',
		description: 'Колонка usergroups.allow_up_image',
	)]
	public int $allow_up_image = 0;
	#[OA\Property(
		property: 'allow_up_watermark',
		type: 'integer',
		description: 'Колонка usergroups.allow_up_watermark',
	)]
	public int $allow_up_watermark = 0;
	#[OA\Property(
		property: 'allow_up_thumb',
		type: 'integer',
		description: 'Колонка usergroups.allow_up_thumb',
	)]
	public int $allow_up_thumb = 0;
	#[OA\Property(
		property: 'up_count_image',
		type: 'integer',
		description: 'Колонка usergroups.up_count_image',
	)]
	public int $up_count_image = 0;
	#[OA\Property(
		property: 'up_image_side',
		type: 'string',
		description: 'Колонка usergroups.up_image_side',
	)]
	public string $up_image_side = '';
	#[OA\Property(
		property: 'up_image_size',
		type: 'integer',
		description: 'Колонка usergroups.up_image_size',
	)]
	public int $up_image_size = 0;
	#[OA\Property(
		property: 'up_thumb_size',
		type: 'string',
		description: 'Колонка usergroups.up_thumb_size',
	)]
	public string $up_thumb_size = '';
	#[OA\Property(
		property: 'allow_mail_files',
		type: 'integer',
		description: 'Колонка usergroups.allow_mail_files',
	)]
	public int $allow_mail_files = 0;
	#[OA\Property(
		property: 'max_mail_files',
		type: 'integer',
		description: 'Колонка usergroups.max_mail_files',
	)]
	public int $max_mail_files = 0;
	#[OA\Property(
		property: 'max_mail_allfiles',
		type: 'integer',
		description: 'Колонка usergroups.max_mail_allfiles',
	)]
	public int $max_mail_allfiles = 0;
	#[OA\Property(
		property: 'mail_files_type',
		type: 'string',
		description: 'Колонка usergroups.mail_files_type',
	)]
	public string $mail_files_type = '';
	#[OA\Property(
		property: 'video_comments',
		type: 'integer',
		description: 'Колонка usergroups.video_comments',
	)]
	public int $video_comments = 0;
	#[OA\Property(
		property: 'media_comments',
		type: 'integer',
		description: 'Колонка usergroups.media_comments',
	)]
	public int $media_comments = 0;
	#[OA\Property(
		property: 'min_image_side',
		type: 'string',
		description: 'Колонка usergroups.min_image_side',
	)]
	public string $min_image_side = '';
	#[OA\Property(
		property: 'allow_public_file_upload',
		type: 'integer',
		description: 'Колонка usergroups.allow_public_file_upload',
	)]
	public int $allow_public_file_upload = 0;
	#[OA\Property(
		property: 'force_comments_rating',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_rating',
	)]
	public int $force_comments_rating = 0;
	#[OA\Property(
		property: 'force_comments_rating_count',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_rating_count',
	)]
	public int $force_comments_rating_count = 0;
	#[OA\Property(
		property: 'force_comments_rating_group',
		type: 'integer',
		description: 'Колонка usergroups.force_comments_rating_group',
	)]
	public int $force_comments_rating_group = 0;
	#[OA\Property(
		property: 'max_downloads',
		type: 'integer',
		description: 'Колонка usergroups.max_downloads',
	)]
	public int $max_downloads = 0;
	#[OA\Property(
		property: 'admin_links',
		type: 'integer',
		description: 'Колонка usergroups.admin_links',
	)]
	public int $admin_links = 0;
	#[OA\Property(
		property: 'admin_meta',
		type: 'integer',
		description: 'Колонка usergroups.admin_meta',
	)]
	public int $admin_meta = 0;
	#[OA\Property(
		property: 'admin_redirects',
		type: 'integer',
		description: 'Колонка usergroups.admin_redirects',
	)]
	public int $admin_redirects = 0;
	#[OA\Property(
		property: 'allow_change_storage',
		type: 'integer',
		description: 'Колонка usergroups.allow_change_storage',
	)]
	public int $allow_change_storage = 0;
	#[OA\Property(
		property: 'self_delete',
		type: 'integer',
		description: 'Колонка usergroups.self_delete',
	)]
	public int $self_delete = 2;
	#[OA\Property(
		property: 'allow_complaint_news',
		type: 'integer',
		description: 'Колонка usergroups.allow_complaint_news',
	)]
	public int $allow_complaint_news = 1;
	#[OA\Property(
		property: 'allow_complaint_comments',
		type: 'integer',
		description: 'Колонка usergroups.allow_complaint_comments',
	)]
	public int $allow_complaint_comments = 1;
	#[OA\Property(
		property: 'allow_complaint_orfo',
		type: 'integer',
		description: 'Колонка usergroups.allow_complaint_orfo',
	)]
	public int $allow_complaint_orfo = 1;
	#[OA\Property(
		property: 'flood_time',
		type: 'integer',
		description: 'Колонка usergroups.flood_time',
	)]
	public int $flood_time = 0;
	#[OA\Property(
		property: 'max_c_negative',
		type: 'integer',
		description: 'Колонка usergroups.max_c_negative',
	)]
	public int $max_c_negative = 0;
	#[OA\Property(
		property: 'max_n_negative',
		type: 'integer',
		description: 'Колонка usergroups.max_n_negative',
	)]
	public int $max_n_negative = 0;
	#[OA\Property(
		property: 'rating_n_day',
		type: 'integer',
		description: 'Колонка usergroups.rating_n_day',
	)]
	public int $rating_n_day = 0;
	#[OA\Property(
		property: 'rating_c_day',
		type: 'integer',
		description: 'Колонка usergroups.rating_c_day',
	)]
	public int $rating_c_day = 0;
	#[OA\Property(
		property: 'allow_rating_change',
		type: 'integer',
		description: 'Колонка usergroups.allow_rating_change',
	)]
	public int $allow_rating_change = 1;
	#[OA\Property(
		property: 'allow_crating_change',
		type: 'integer',
		description: 'Колонка usergroups.allow_crating_change',
	)]
	public int $allow_crating_change = 1;

	public function table(): string {
		return 'usergroups';
	}

	protected function columnList(): array {
		return [
			'id',
			'group_name',
			'allow_cats',
			'allow_adds',
			'cat_add',
			'allow_admin',
			'allow_addc',
			'allow_editc',
			'allow_delc',
			'edit_allc',
			'del_allc',
			'moderation',
			'allow_all_edit',
			'allow_edit',
			'allow_pm',
			'max_pm',
			'max_foto',
			'allow_files',
			'allow_hide',
			'allow_short',
			'time_limit',
			'rid',
			'allow_fixed',
			'allow_feed',
			'allow_search',
			'allow_poll',
			'allow_main',
			'captcha',
			'icon',
			'allow_modc',
			'allow_rating',
			'allow_offline',
			'allow_image_upload',
			'allow_file_upload',
			'allow_signature',
			'allow_url',
			'news_sec_code',
			'allow_image',
			'max_signature',
			'max_info',
			'admin_addnews',
			'admin_editnews',
			'admin_comments',
			'admin_categories',
			'admin_editusers',
			'admin_wordfilter',
			'admin_xfields',
			'admin_userfields',
			'admin_static',
			'admin_editvote',
			'admin_newsletter',
			'admin_blockip',
			'admin_banners',
			'admin_rss',
			'admin_iptools',
			'admin_rssinform',
			'admin_googlemap',
			'allow_html',
			'group_prefix',
			'group_suffix',
			'allow_subscribe',
			'allow_image_size',
			'cat_allow_addnews',
			'flood_news',
			'max_day_news',
			'force_leech',
			'edit_limit',
			'captcha_pm',
			'max_pm_day',
			'max_mail_day',
			'admin_tagscloud',
			'allow_vote',
			'admin_complaint',
			'news_question',
			'comments_question',
			'max_comment_day',
			'max_images',
			'max_files',
			'disable_news_captcha',
			'disable_comments_captcha',
			'pm_question',
			'captcha_feedback',
			'feedback_question',
			'files_type',
			'max_file_size',
			'files_max_speed',
			'spamfilter',
			'allow_comments_rating',
			'max_edit_days',
			'spampmfilter',
			'force_reg',
			'force_reg_days',
			'force_reg_group',
			'force_news',
			'force_news_count',
			'force_news_group',
			'force_comments',
			'force_comments_count',
			'force_comments_group',
			'force_rating',
			'force_rating_count',
			'force_rating_group',
			'not_allow_cats',
			'allow_up_image',
			'allow_up_watermark',
			'allow_up_thumb',
			'up_count_image',
			'up_image_side',
			'up_image_size',
			'up_thumb_size',
			'allow_mail_files',
			'max_mail_files',
			'max_mail_allfiles',
			'mail_files_type',
			'video_comments',
			'media_comments',
			'min_image_side',
			'allow_public_file_upload',
			'force_comments_rating',
			'force_comments_rating_count',
			'force_comments_rating_group',
			'max_downloads',
			'admin_links',
			'admin_meta',
			'admin_redirects',
			'allow_change_storage',
			'self_delete',
			'allow_complaint_news',
			'allow_complaint_comments',
			'allow_complaint_orfo',
			'flood_time',
			'max_c_negative',
			'max_n_negative',
			'rating_n_day',
			'rating_c_day',
			'allow_rating_change',
			'allow_crating_change',
		];
	}

	protected function defaultMap(): array {
		return [
			'group_name' => '',
			'allow_cats' => '',
			'allow_adds' => 1,
			'cat_add' => '',
			'allow_admin' => 0,
			'allow_addc' => 0,
			'allow_editc' => 0,
			'allow_delc' => 0,
			'edit_allc' => 0,
			'del_allc' => 0,
			'moderation' => 0,
			'allow_all_edit' => 0,
			'allow_edit' => 0,
			'allow_pm' => 0,
			'max_pm' => 0,
			'max_foto' => '',
			'allow_files' => 0,
			'allow_hide' => 1,
			'allow_short' => 0,
			'time_limit' => 0,
			'rid' => 0,
			'allow_fixed' => 0,
			'allow_feed' => 1,
			'allow_search' => 1,
			'allow_poll' => 1,
			'allow_main' => 1,
			'captcha' => 0,
			'icon' => '',
			'allow_modc' => 0,
			'allow_rating' => 1,
			'allow_offline' => 0,
			'allow_image_upload' => 0,
			'allow_file_upload' => 0,
			'allow_signature' => 0,
			'allow_url' => 1,
			'news_sec_code' => 1,
			'allow_image' => 0,
			'max_signature' => 0,
			'max_info' => 0,
			'admin_addnews' => 0,
			'admin_editnews' => 0,
			'admin_comments' => 0,
			'admin_categories' => 0,
			'admin_editusers' => 0,
			'admin_wordfilter' => 0,
			'admin_xfields' => 0,
			'admin_userfields' => 0,
			'admin_static' => 0,
			'admin_editvote' => 0,
			'admin_newsletter' => 0,
			'admin_blockip' => 0,
			'admin_banners' => 0,
			'admin_rss' => 0,
			'admin_iptools' => 0,
			'admin_rssinform' => 0,
			'admin_googlemap' => 0,
			'allow_html' => 1,
			'group_prefix' => '',
			'group_suffix' => '',
			'allow_subscribe' => 0,
			'allow_image_size' => 0,
			'cat_allow_addnews' => '',
			'flood_news' => 0,
			'max_day_news' => 0,
			'force_leech' => 0,
			'edit_limit' => 0,
			'captcha_pm' => 0,
			'max_pm_day' => 0,
			'max_mail_day' => 0,
			'admin_tagscloud' => 0,
			'allow_vote' => 0,
			'admin_complaint' => 0,
			'news_question' => 0,
			'comments_question' => 0,
			'max_comment_day' => 0,
			'max_images' => 0,
			'max_files' => 0,
			'disable_news_captcha' => 0,
			'disable_comments_captcha' => 0,
			'pm_question' => 0,
			'captcha_feedback' => 1,
			'feedback_question' => 0,
			'files_type' => '',
			'max_file_size' => 0,
			'files_max_speed' => 0,
			'spamfilter' => 2,
			'allow_comments_rating' => 1,
			'max_edit_days' => 0,
			'spampmfilter' => 0,
			'force_reg' => 0,
			'force_reg_days' => 0,
			'force_reg_group' => 4,
			'force_news' => 0,
			'force_news_count' => 0,
			'force_news_group' => 4,
			'force_comments' => 0,
			'force_comments_count' => 0,
			'force_comments_group' => 4,
			'force_rating' => 0,
			'force_rating_count' => 0,
			'force_rating_group' => 4,
			'not_allow_cats' => '',
			'allow_up_image' => 0,
			'allow_up_watermark' => 0,
			'allow_up_thumb' => 0,
			'up_count_image' => 0,
			'up_image_side' => '',
			'up_image_size' => 0,
			'up_thumb_size' => '',
			'allow_mail_files' => 0,
			'max_mail_files' => 0,
			'max_mail_allfiles' => 0,
			'mail_files_type' => '',
			'video_comments' => 0,
			'media_comments' => 0,
			'min_image_side' => '',
			'allow_public_file_upload' => 0,
			'force_comments_rating' => 0,
			'force_comments_rating_count' => 0,
			'force_comments_rating_group' => 0,
			'max_downloads' => 0,
			'admin_links' => 0,
			'admin_meta' => 0,
			'admin_redirects' => 0,
			'allow_change_storage' => 0,
			'self_delete' => 2,
			'allow_complaint_news' => 1,
			'allow_complaint_comments' => 1,
			'allow_complaint_orfo' => 1,
			'flood_time' => 0,
			'max_c_negative' => 0,
			'max_n_negative' => 0,
			'rating_n_day' => 0,
			'rating_c_day' => 0,
			'allow_rating_change' => 1,
			'allow_crating_change' => 1,
		];
	}

	public function primaryKey(): string|array {
		return 'id';
	}
}
>>>>>>> Current commit: Начало обновления до api v2
