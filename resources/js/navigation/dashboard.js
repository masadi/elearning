export default [
  /*
  {
    title: 'Nav Levels',
    icon: { icon: 'tabler-menu-2' },
    children: [
      {
        title: 'Level 2.1',
        to: null,
      },
      {
        title: 'Level 2.2',
        children: [
          {
            title: 'Level 3.1',
            to: null,
          },
          {
            title: 'Level 3.2',
            to: null,
          },
        ],
      },
    ],
  },
  */
  {
    title: 'Beranda',
    to: { name: 'root' },
    icon: { icon: 'tabler-smart-home' },
    action: 'read',
    subject: 'dashboard-read',
  },
]
