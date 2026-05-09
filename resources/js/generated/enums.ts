/** AUTO-GENERATED FILE. DO NOT EDIT. */

export const ItemHistoryActionType = {
  CREATED: 0,
  UPDATED: 1,
  IMAGE_ADDED: 2,
  STATUS_CHANGED: 3,
  MATCHED: 4,
  RESOLVED: 5,
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
  MATCHED: 1,
  RESOLVED: 2,
  ARCHIVED: 3,
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

