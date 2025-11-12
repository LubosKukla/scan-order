import { createRouter, createWebHistory } from 'vue-router';

// Helper auth/role checks (nahraďte vlastnou logikou)
function isAdmin() {
  try {
    const role = localStorage.getItem('role');
    return role === 'admin';
  } catch (e) {
    return false;
  }
}

const routes = [
  // Klasický web (root)
  {
    path: '/',
    name: 'web-domov',
    meta: { section: 'web' },
    component: () => import('../views/web/DomovView.vue'),
  },
  {
    path: '/o-nas',
    name: 'web-o-nas',
    meta: { section: 'web' },
    component: () => import('../views/web/ONasView.vue'),
  },
  {
    path: '/cennik',
    name: 'web-cennik',
    meta: { section: 'web' },
    component: () => import('../views/web/CennikView.vue'),
  },
  {
    path: '/ako-to-funguje',
    name: 'web-ako-to-funguje',
    meta: { section: 'web' },
    component: () => import('../views/web/AkoToFungujeView.vue'),
  },
  {
    path: '/kontakt',
    name: 'web-kontakt',
    meta: { section: 'web' },
    component: () => import('../views/web/KontaktView.vue'),
  },

  // Reštaurácia
  {
    path: '/restauracia/loading',
    name: 'restauracia-loading',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/LoadingView.vue'),
  },
  {
    path: '/restauracia/menu',
    name: 'restauracia-menu',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/MenuView.vue'),
  },
  {
    path: '/restauracia/produkt/:id',
    name: 'restauracia-produkt-detail',
    meta: { section: 'restauracia' },
    props: true,
    component: () => import('../views/restauracia/ProduktDetailView.vue'),
  },
  {
    path: '/restauracia/pridat-recenziu',
    name: 'restauracia-pridat-recenziu',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/PridatRecenziuView.vue'),
  },
  {
    path: '/restauracia/kosik',
    name: 'restauracia-kosik',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/KosikView.vue'),
  },
  {
    path: '/restauracia/pokladna',
    name: 'restauracia-pokladna',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/PokladnaView.vue'),
  },
  {
    path: '/restauracia/login',
    name: 'restauracia-login',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/LoginView.vue'),
  },
  {
    path: '/restauracia/registracia',
    name: 'restauracia-registracia',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/RegistraciaView.vue'),
  },
  {
    path: '/restauracia/zabudnute-heslo',
    name: 'restauracia-zabudnute-heslo',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/ZabudnuteHesloView.vue'),
  },
  {
    path: '/restauracia/zmena-zabudnuteho-hesla',
    name: 'restauracia-zmena-zabudnuteho-hesla',
    meta: { section: 'restauracia' },
    component: () => import('../views/restauracia/ZmenaZabudnutehoHeslaView.vue'),
  },

  // Admin
  {
    path: '/admin/login',
    name: 'admin-login',
    meta: { section: 'admin' },
    component: () => import('../views/admin/LoginView.vue'),
  },
  {
    path: '/admin/register',
    name: 'admin-register',
    meta: { section: 'admin' },
    component: () => import('../views/admin/RegisterView.vue'),
  },
  {
    path: '/admin/zabudnute-heslo',
    name: 'admin-zabudnute-heslo',
    meta: { section: 'admin' },
    component: () => import('../views/admin/ZabudnuteHesloView.vue'),
  },
  {
    path: '/admin/zmena-zabudnuteho-hesla',
    name: 'admin-zmena-zabudnuteho-hesla',
    meta: { section: 'admin' },
    component: () => import('../views/admin/ZmenaZabudnutehoHeslaView.vue'),
  },
  {
    path: '/admin/prehlad',
    name: 'admin-prehlad',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/PrehladView.vue'),
  },
  {
    path: '/admin/sprava-menu',
    name: 'admin-sprava-menu',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/SpravaMenuView.vue'),
  },
  {
    path: '/admin/nastavenia',
    name: 'admin-nastavenia',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/NastaveniaView.vue'),
  },
  {
    path: '/admin/qr-kody',
    name: 'admin-qr-kody',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/QRKodyView.vue'),
  },
  {
    path: '/admin/recenzie',
    name: 'admin-recenzie',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/RecenzieView.vue'),
  },
  {
    path: '/admin/zakaznici',
    name: 'admin-zakaznici',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/ZakazniciView.vue'),
  },
  {
    path: '/admin/objednavky',
    name: 'admin-objednavky',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/ObjednavkyView.vue'),
  },
  {
    path: '/admin/zamestnanci',
    name: 'admin-zamestnanci',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/ZamestnanciView.vue'),
  },
  {
    path: '/admin/statistiky',
    name: 'admin-statistiky',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/StatistikyView.vue'),
  },
  {
    path: '/admin/pomoc-a-podpora',
    name: 'admin-pomoc-a-podpora',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/PomocPodporaView.vue'),
  },
  {
    path: '/admin/predplatne',
    name: 'admin-predplatne',
    meta: { section: 'admin', requiresRole: 'admin' },
    component: () => import('../views/admin/PredplatneView.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

// Guard: admin sekcia len pre admina, ostatné pre všetkých
router.beforeEach((to, from, next) => {
  const roleRequired = to.matched.find((r) => r.meta && r.meta.requiresRole)?.meta.requiresRole;
  if (roleRequired === 'admin') {
    if (!isAdmin()) {
      return next({ name: 'admin-login', query: { redirect: to.fullPath } });
    }
  }
  return next();
});

export default router;
