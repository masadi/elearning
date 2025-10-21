/*import dashboard from './dashboard'
import referensi from './referensi'
import settings from './settings'

export default [...dashboard, ...settings, ...referensi]*/
export default [{
    title: 'Beranda',
    to: {
      name: 'dashboard'
    },
    icon: {
      icon: 'tabler-smart-home'
    },
    action: 'read',
    subject: 'dashboard-read',
  },
  {
    title: 'Laman',
    icon: {
      icon: 'tabler-file-info'
    },
    children: [{
        title: 'Tentang',
        to: 'laman-tentang',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'laman-tentang-read',
      },
      {
        title: 'Galeri',
        to: 'laman-galeri',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'laman-galeri-read',
      },
      {
        title: 'Program',
        to: 'laman-program',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'laman-program-read',
      },
      {
        title: 'Kontak',
        to: 'laman-kontak',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'laman-kontak-read',
      },
    ],
  },
  {
    title: 'Konten',
    icon: {
      icon: 'tabler-list-check'
    },
    children: [{
        title: 'Slide',
        to: 'konten-slide',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'konten-slide-read',
      },
      {
        title: 'PTK',
        to: 'konten-ptk',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'create',
        subject: 'konten-ptk-create',
      },
      {
        title: 'Visi & Misi',
        to: 'konten-visi-misi',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'create',
        subject: 'konten-visi-misi-create',
      },
    ],
  },
  {
    title: 'Asesmen',
    icon: {
      icon: 'tabler-list'
    },
    children: [{
        title: 'Mata Pelajaran',
        to: 'asesmen-mata-pelajaran',
        icon: {
          icon: 'tabler-list'
        },
        action: 'read',
        subject: 'asesmen-mata-pelajaran-read',
      },
      {
        title: 'Pembelajaran',
        to: 'asesmen-pembelajaran',
        icon: {
          icon: 'tabler-checklist'
        },
        action: 'create',
        subject: 'asesmen-pembelajaran-create',
      },
      {
        title: 'Tes',
        to: 'asesmen-tes',
        icon: {
          icon: 'tabler-zoom-check'
        },
        action: 'create',
        subject: 'asesmen-tes-create',
      },
    ],
  },
  {
    title: 'Buku Induk',
    icon: {
      icon: 'tabler-book'
    },
    children: [{
        title: 'Kelompok Mapel',
        to: 'buku-induk-kelompok-mapel',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'buku-induk-read',
      },
      {
        title: 'Mata Pelajaran',
        to: 'buku-induk-mata-pelajaran',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'buku-induk-read',
      },
      {
        title: 'Peserta Didik',
        to: 'buku-induk-peserta-didik',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'buku-induk-read',
      },
      {
        title: 'Rombongan Belajar',
        to: 'buku-induk-rombongan-belajar',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'buku-induk-read',
      },
      {
        title: 'Input Nilai',
        to: 'buku-induk-input-nilai',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'buku-induk-read',
      },
      {
        title: 'Cetak',
        to: 'buku-induk-cetak',
        icon: {
          icon: 'tabler-hand-finger-right'
        },
        action: 'read',
        subject: 'buku-induk-read',
      },
    ],
  },
  /*{
    title: 'Buku Induk',
    to: 'buku-induk',
    icon: {
      icon: 'tabler-book'
    },
    action: 'read',
    subject: 'buku-induk-read',
  },*/
  /*{
    title: 'Dokumen',
    to: 'dokumen',
    icon: { icon: 'tabler-checklist' },
    action: 'create',
    subject: 'dokumen-create',
  },*/
  {
    title: 'Profile',
    to: 'profile',
    icon: {
      icon: 'tabler-user'
    },
    action: 'read',
    subject: 'profile-read',
  },
]
