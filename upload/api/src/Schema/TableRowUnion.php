<?php

declare(strict_types=1);

namespace DleApi\Schema;

use OpenApi\Attributes as OA;

/**
 * Union всех табличных Schema для POST /table/{name}/.
 * Связывает path {name} с components.schemas (без «declared but never used»).
 */
#[OA\Schema(
	schema: 'TableRow',
	description: 'Тело записи: выберите схему по логическому имени таблицы в path {name} (SchemaRegistry).',
	oneOf: [
		new OA\Schema(ref: AdminLogsSchema::class),
		new OA\Schema(ref: AdminSectionsSchema::class),
		new OA\Schema(ref: BannedSchema::class),
		new OA\Schema(ref: BannersSchema::class),
		new OA\Schema(ref: BannersLogsSchema::class),
		new OA\Schema(ref: BannersRubricsSchema::class),
		new OA\Schema(ref: CategorySchema::class),
		new OA\Schema(ref: CommentRatingLogSchema::class),
		new OA\Schema(ref: CommentsSchema::class),
		new OA\Schema(ref: CommentsFilesSchema::class),
		new OA\Schema(ref: ComplaintSchema::class),
		new OA\Schema(ref: ConversationReadsSchema::class),
		new OA\Schema(ref: ConversationUsersSchema::class),
		new OA\Schema(ref: ConversationsSchema::class),
		new OA\Schema(ref: ConversationsMessagesSchema::class),
		new OA\Schema(ref: DownloadsLogSchema::class),
		new OA\Schema(ref: EmailSchema::class),
		new OA\Schema(ref: FilesSchema::class),
		new OA\Schema(ref: FloodSchema::class),
		new OA\Schema(ref: IgnoreListSchema::class),
		new OA\Schema(ref: ImagesSchema::class),
		new OA\Schema(ref: LinksSchema::class),
		new OA\Schema(ref: LoginLogSchema::class),
		new OA\Schema(ref: LogsSchema::class),
		new OA\Schema(ref: LostdbSchema::class),
		new OA\Schema(ref: MailLogSchema::class),
		new OA\Schema(ref: MetatagsSchema::class),
		new OA\Schema(ref: NewsletterTemplateCategoriesSchema::class),
		new OA\Schema(ref: NewsletterTemplateItemsSchema::class),
		new OA\Schema(ref: NoticeSchema::class),
		new OA\Schema(ref: PluginsSchema::class),
		new OA\Schema(ref: PluginsFilesSchema::class),
		new OA\Schema(ref: PluginsLogsSchema::class),
		new OA\Schema(ref: PollSchema::class),
		new OA\Schema(ref: PollLogSchema::class),
		new OA\Schema(ref: PostSchema::class),
		new OA\Schema(ref: PostExtrasSchema::class),
		new OA\Schema(ref: PostExtrasCatsSchema::class),
		new OA\Schema(ref: PostLogSchema::class),
		new OA\Schema(ref: PostPassSchema::class),
		new OA\Schema(ref: QuestionSchema::class),
		new OA\Schema(ref: ReadLogSchema::class),
		new OA\Schema(ref: RedirectsSchema::class),
		new OA\Schema(ref: RssSchema::class),
		new OA\Schema(ref: RssinformSchema::class),
		new OA\Schema(ref: SendlogSchema::class),
		new OA\Schema(ref: SocialLoginSchema::class),
		new OA\Schema(ref: SpamLogSchema::class),
		new OA\Schema(ref: StaticSchema::class),
		new OA\Schema(ref: StaticFilesSchema::class),
		new OA\Schema(ref: StorageSchema::class),
		new OA\Schema(ref: SubscribeSchema::class),
		new OA\Schema(ref: TagsSchema::class),
		new OA\Schema(ref: TwofactorSchema::class),
		new OA\Schema(ref: UsergroupsSchema::class),
		new OA\Schema(ref: UsersSchema::class),
		new OA\Schema(ref: UsersDeleteSchema::class),
		new OA\Schema(ref: ViewsSchema::class),
		new OA\Schema(ref: VoteSchema::class),
		new OA\Schema(ref: VoteResultSchema::class),
		new OA\Schema(ref: XfsearchSchema::class),
	],
)]
final class TableRowUnion {
}
