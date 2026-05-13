/** AUTO-GENERATED FILE. DO NOT EDIT. */

export const ItemHistoryActionType = {
  CREATED: 0,
  UPDATED: 1,
  DELETED: 2,
  RESTORED: 3,
  ARCHIVED: 4,
  IMAGE_ADDED: 5,
  IMAGE_REMOVED: 6,
  PRIMARY_IMAGE_CHANGED: 7,
  STATUS_CHANGED: 8,
  MARKED_FOUND: 9,
  FOUND_APPROVED: 10,
  FOUND_REJECTED: 11,
  MATCHED: 12,
  MATCH_REMOVED: 13,
  CLAIMED: 14,
  REPORTED: 15,
  REVIEWED_BY_ADMIN: 16,
  CONTACTED_OWNER: 17,
  CONTACTED_FINDER: 18,
} as const;

export type ItemHistoryActionTypeType = typeof ItemHistoryActionType[keyof typeof ItemHistoryActionType];

export const ItemMatchStatus = {
  PENDING: 0,
  CONFIRMED: 1,
  REJECTED: 2,
} as const;

export type ItemMatchStatusType = typeof ItemMatchStatus[keyof typeof ItemMatchStatus];

export const ItemReportStatus = {
  PENDING: 0,
  CONFIRMED: 1,
  REJECTED: 2,
} as const;

export type ItemReportStatusType = typeof ItemReportStatus[keyof typeof ItemReportStatus];

export const ItemStatus = {
  LOST: 0,
  FOUND_PENDING: 1,
  FOUND: 2,
  CLAIMED: 3,
} as const;

export type ItemStatusType = typeof ItemStatus[keyof typeof ItemStatus];

export const PermissionStatus = {
  ACTIVE: 1,
  INACTIVE: 0,
} as const;

export type PermissionStatusType = typeof PermissionStatus[keyof typeof PermissionStatus];

export const RoleStatus = {
  ACTIVE: 1,
  INACTIVE: 0,
} as const;

export type RoleStatusType = typeof RoleStatus[keyof typeof RoleStatus];

export const Status = {
  ACTIVE: 1,
  INACTIVE: 0,
} as const;

export type StatusType = typeof Status[keyof typeof Status];

export const UserStatus = {
  DEACTIVATED: 0,
  ACTIVE: 1,
  PENDING: 2,
  SUSPENDED: 5,
} as const;

export type UserStatusType = typeof UserStatus[keyof typeof UserStatus];

