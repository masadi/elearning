/*import dashboard from './dashboard'
import referensi from './referensi'
import settings from './settings'

export default [...dashboard, ...settings, ...referensi]*/
export default [
    {
        title: 'Beranda',
        to: { name: 'root' },
        icon: { icon: 'tabler-smart-home' },
        action: 'read',
        subject: 'dashboard-read',
    },
    {
    title: 'Referensi',
    icon: { icon: 'tabler-list' },
    children: [
      {
        title: 'Sekolah',
        to: 'referensi-sekolah',
        icon: { icon: 'tabler-hand-finger-right' },
        action: 'read',
        subject: 'referensi-sekolah-read',
      },
      {
        title: 'PTK',
        to: 'referensi-ptk',
        icon: { icon: 'tabler-hand-finger-right' },
        action: 'read',
        subject: 'referensi-ptk-read',
      },
      {
        title: 'Rombongan Belajar',
        to: 'referensi-rombel',
        icon: { icon: 'tabler-hand-finger-right' },
        action: 'read',
        subject: 'referensi-rombel-read',
      },
      {
        title: 'Mata Pelajaran',
        to: 'referensi-mata-pelajaran',
        icon: { icon: 'tabler-hand-finger-right' },
        action: 'read',
        subject: 'referensi-mata-pelajaran-read',
      },
    ],
  },
  /*{
    title: 'Materi Ajar',
    to: 'materi-ajar',
    icon: { icon: 'tabler-checklist' },
    action: 'read',
    subject: 'materi-ajar-read',
  },*/
  {
    title: 'Pelatihan',
    to: 'pelatihan',
    icon: { icon: 'tabler-checklist' },
    action: 'read',
    subject: 'pelatihan-read',
  },
    {
        title: 'Profile',
        to: 'profile',
        icon: { icon: 'tabler-user' },
        action: 'read',
        subject: 'profile-read',
    },
]
