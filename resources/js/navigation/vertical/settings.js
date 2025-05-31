export default [
  {
    title: 'Settings',
    icon: { icon: 'tabler-settings' },
    children: [
      {
        title: 'Users & Roles',
        to: 'settings-users-roles',
        icon: { icon: 'tabler-users' },
        action: 'read',
        subject: 'setting-users-roles-read',
      },
      {
        title: 'Permissions',
        to: 'settings-permissions',
        icon: { icon: 'tabler-accessible' },
        action: 'read',
        subject: 'setting-permissions-read',
      },
      {
        title: 'Profile',
        to: 'settings-profile',
        icon: { icon: 'tabler-user' },
        action: 'read',
        subject: 'setting-profile-read',
      },
    ],
  },
]
