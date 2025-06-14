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
  {
    title: 'Pelatihan',
    to: 'pelatihan',
    icon: { icon: 'tabler-checklist' },
    action: 'create',
    subject: 'pelatihan-create',
  },
  /*{
    title: 'Sesi',
    to: 'sesi',
    icon: { icon: 'tabler-checklist' },
    action: 'create',
    subject: 'sesi-create',
  },
  {
    title: 'Materi',
    to: 'materi',
    icon: { icon: 'tabler-checklist' },
    action: 'create',
    subject: 'materi-create',
  },
  {
    title: 'Dokumen',
    to: 'dokumen',
    icon: { icon: 'tabler-checklist' },
    action: 'create',
    subject: 'dokumen-create',
  },*/
  {
    title: 'Profile',
    to: 'profile',
    icon: { icon: 'tabler-user' },
    action: 'read',
    subject: 'profile-read',
  },
]
