import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store';

// Helper auth/role checks
// function isAdmin() {
//   try {
//     const role = localStorage.getItem('role');
//     return role === 'admin';
//   } catch (e) {
//     return false;
//   }
// }

const routes = [
  // Klasický web (root)
  {
    path: '/',
    name: 'web-domov',
    meta: { section: 'web', title: 'Domov' },
    component: () => import('../views/web/DomovView.vue'),
  },
  {
    path: '/o-nas',
    name: 'web-o-nas',
    meta: { section: 'web', title: 'O nás' },
    component: () => import('../views/web/ONasView.vue'),
  },
  {
    path: '/cennik',
    name: 'web-cennik',
    meta: { section: 'web', title: 'Cenník' },
    component: () => import('../views/web/CennikView.vue'),
  },
  {
    path: '/ako-to-funguje',
    name: 'web-ako-to-funguje',
    meta: { section: 'web', title: 'Ako to funguje' },
    component: () => import('../views/web/AkoToFungujeView.vue'),
  },
  {
    path: '/kontakt',
    name: 'web-kontakt',
    meta: { section: 'web', title: 'Kontakt' },
    component: () => import('../views/web/KontaktView.vue'),
  },

  // Reštaurácia
  {
    path: '/restauracia/loading',
    name: 'restauracia-loading',
    meta: { section: 'restauracia', title: 'Načítavanie' },
    component: () => import('../views/restauracia/LoadingView.vue'),
  },
  {
    path: '/restauracia/menu',
    name: 'restauracia-menu',
    meta: { section: 'restauracia', title: 'Menu' },
    component: () => import('../views/restauracia/MenuView.vue'),
  },
  {
    path: '/restauracia/produkt/:id',
    name: 'restauracia-produkt-detail',
    meta: { section: 'restauracia', title: 'Detail produktu' },
    props: true,
    component: () => import('../views/restauracia/ProduktDetailView.vue'),
  },
  {
    path: '/restauracia/pridat-recenziu',
    name: 'restauracia-pridat-recenziu',
    meta: { section: 'restauracia', title: 'Pridať recenziu' },
    component: () => import('../views/restauracia/PridatRecenziuView.vue'),
  },
  {
    path: '/restauracia/kosik',
    name: 'restauracia-kosik',
    meta: { section: 'restauracia', title: 'Košík' },
    component: () => import('../views/restauracia/KosikView.vue'),
  },
  {
    path: '/restauracia/pokladna',
    name: 'restauracia-pokladna',
    meta: { section: 'restauracia', title: 'Pokladňa' },
    component: () => import('../views/restauracia/PokladnaView.vue'),
  },
  {
    path: '/restauracia/login',
    name: 'restauracia-login',
    meta: { section: 'restauracia', title: 'Prihlásenie' },
    component: () => import('../views/restauracia/LoginView.vue'),
  },
  {
    path: '/restauracia/registracia',
    name: 'restauracia-registracia',
    meta: { section: 'restauracia', title: 'Registrácia' },
    component: () => import('../views/restauracia/RegistraciaView.vue'),
  },
  {
    path: '/restauracia/zabudnute-heslo',
    name: 'restauracia-zabudnute-heslo',
    meta: { section: 'restauracia', title: 'Zabudnuté heslo' },
    component: () => import('../views/restauracia/ZabudnuteHesloView.vue'),
  },
  {
    path: '/restauracia/zmena-zabudnuteho-hesla',
    name: 'restauracia-zmena-zabudnuteho-hesla',
    meta: { section: 'restauracia', title: 'Zmena hesla' },
    component: () => import('../views/restauracia/ZmenaZabudnutehoHeslaView.vue'),
  },

  // Admin
  {
    path: '/admin/login',
    name: 'admin-login',
    meta: { section: 'admin', title: 'Prihlásenie', showInMore: true },
    component: () => import('../views/admin/LoginView.vue'),
  },
  {
    path: '/admin/register',
    name: 'admin-register',
    meta: { section: 'admin', title: 'Registrácia', showInMore: true },
    component: () => import('../views/admin/RegisterView.vue'),
  },
  {
    path: '/admin/zabudnute-heslo',
    name: 'admin-zabudnute-heslo',
    meta: { section: 'admin', title: 'Zabudnuté heslo', showInMore: true },
    component: () => import('../views/admin/ZabudnuteHesloView.vue'),
  },
  {
    path: '/admin/zmena-zabudnuteho-hesla',
    name: 'admin-zmena-zabudnuteho-hesla',
    meta: { section: 'admin', title: 'Zmena hesla', showInMore: true },
    component: () => import('../views/admin/ZmenaZabudnutehoHeslaView.vue'),
  },
  {
    path: '/admin/zmena-zabudnuteho-hesla-po-emaili',
    name: 'admin-zmena-zabudnuteho-hesla-po-emaili',
    meta: { section: 'admin', title: 'Nastavenie nového hesla' },
    component: () => import('../views/admin/ZmenaZabudnutehoHeslaPoEmailiView.vue'),
  },
  {
    path: '/admin/prehlad',
    name: 'admin-prehlad',
    meta: { section: 'admin', title: 'Prehľad', requiresRole: 'admin' },
    component: () => import('../views/admin/PrehladView.vue'),
  },
  {
    path: '/admin/sprava-menu',
    name: 'admin-sprava-menu',
    meta: { section: 'admin', title: 'Správa menu', requiresRole: 'admin' },
    component: () => import('../views/admin/SpravaMenuView.vue'),
  },
  {
    path: '/admin/nastavenia',
    name: 'admin-nastavenia',
    meta: { section: 'admin', title: 'Nastavenia', requiresRole: 'admin' },
    component: () => import('../views/admin/NastaveniaView.vue'),
  },
  {
    path: '/admin/predplatne',
    name: 'admin-predplatne',
    meta: { section: 'admin', title: 'Predplatne', requiresRole: 'admin' },
    component: () => import('../views/admin/PredplatneView.vue'),
  },
  {
    path: '/admin/qr-kody',
    name: 'admin-qr-kody',
    meta: { section: 'admin', title: 'QR kódy', requiresRole: 'admin' },
    component: () => import('../views/admin/QRKodyView.vue'),
  },
  {
    path: '/admin/recenzie',
    name: 'admin-recenzie',
    meta: { section: 'admin', title: 'Recenzie', requiresRole: 'admin' },
    component: () => import('../views/admin/RecenzieView.vue'),
  },
  {
    path: '/admin/zakaznici',
    name: 'admin-zakaznici',
    meta: { section: 'admin', title: 'Zákazníci', requiresRole: 'admin' },
    component: () => import('../views/admin/ZakazniciView.vue'),
  },
  {
    path: '/admin/objednavky',
    name: 'admin-objednavky',
    meta: { section: 'admin', title: 'Objednávky', requiresRole: 'admin' },
    component: () => import('../views/admin/ObjednavkyView.vue'),
  },
  {
    path: '/admin/zamestnanci',
    name: 'admin-zamestnanci',
    meta: { section: 'admin', title: 'Zamestnanci', requiresRole: 'admin' },
    component: () => import('../views/admin/ZamestnanciView.vue'),
  },
  {
    path: '/admin/statistiky',
    name: 'admin-statistiky',
    meta: { section: 'admin', title: 'Štatistiky', requiresRole: 'admin' },
    component: () => import('../views/admin/StatistikyView.vue'),
  },
  {
    path: '/admin/pomoc-a-podpora',
    name: 'admin-pomoc-a-podpora',
    meta: { section: 'admin', title: 'Pomoc a podpora', requiresRole: 'admin' },
    component: () => import('../views/admin/PomocPodporaView.vue'),
  },
  {
    path: '/admin/predplatne',
    name: 'admin-predplatne',
    meta: { section: 'admin', title: 'Predplatné', requiresRole: 'admin' },
    component: () => import('../views/admin/PredplatneView.vue'),
  },

  //404
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    meta: { section: 'web', title: 'Stránka nenájdená', showInMore: true },
    component: () => import('../views/web/NotFoundView.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    }
    return { left: 0, top: 0 };
  },
});

router.beforeEach(async (to, from, next) => {
  const requiresAdmin = to.matched.some((r) => r.meta && r.meta.requiresRole === 'admin');
  const isLoggedIn = store.getters['user/isLoggedIn'];

  // Ak idem na admin a ešte nemám usera, skúsim ho načítať
  if (requiresAdmin && !isLoggedIn) {
    try {
      await store.dispatch('user/fetchCurrentUser');
    } catch (e) {
      // ignore
    }
    if (!store.getters['user/isLoggedIn']) {
      return next({ name: 'admin-login' });
    }
  }

  // Už prihlásený admin nech nejde zbytočne na login
  if (to.name === 'admin-login' && store.getters['user/isLoggedIn']) {
    return next({ name: 'admin-prehlad' });
  }

  return next();
});

export default router;
