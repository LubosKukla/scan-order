//Dokumentácia bola generovaná AI

# Komponentová dokumentácia

Tento dokument sumarizuje dostupné UI komponenty a layoutové prvky v projekte a opisuje spôsob ich použitia. Slúži ako referenčný materiál pre frontend tím.

## 1. Základné komponenty (`Base*`)

### 1.1 BaseButton (`web/src/components/global/buttons/BaseButton.vue`)

- **Účel:** jednotné tlačidlo; podporuje varianty a vstavané ikony z FontAwesome.
- **Hlavné props:**
  - `type`: `button` | `submit` | `reset` (default `button`)
  - `variant`: `primary` | `secondary`
  - `icon`: kľúč z mapy (`add`, `send`, `login`, `download`, …)
  - `disabled`: `Boolean`
- **Príklad:**  
  `<BaseButton icon="add" @click="openModal">Pridať položku</BaseButton>`

### 1.2 BaseInput (`web/src/components/global/inputs/BaseInput.vue`)

- **Účel:** textové pole s podporou chýb, prepínača hesla a prístupnosti.
- **Prop/emit:** `modelValue`, `type`, `label`, `placeholder`, `required`, `error`, `@update:modelValue`.
- **Poznámka:** `error` mení štýl okraja a zobrazuje text pod inputom.

### 1.3 BaseTextarea (`web/src/components/global/inputs/BaseTextarea.vue`)

- Rovnaké API ako BaseInput; navyše `rows`, `maxlength`.

### 1.4 BaseSelect (`web/src/components/global/inputs/BaseSelect.vue`)

- **Účel:** vlastný select s dropdownom.
- **Prop:** `modelValue`, `options`, `optionLabelKey`, `optionValueKey`, `name`, `placeholder`, `required`, `error`.
- **Emit:** `@update:modelValue`.
- **Poznámka:** pri komplexnejšom obsahu odovzdaj pole objektov (`[{ label, value }]`).

### 1.5 BaseToggle (`web/src/components/global/inputs/BaseToggle.vue`)

- **Účel:** toggle/checkbox; používa sa s `v-model`.
- **Prop:** `modelValue`, `label`, `disabled`.

### 1.6 BaseCard (`web/src/components/global/containers/BaseCard.vue`)

- **Účel:** kontajner s bielym pozadím, borderom a tieňom.  
  Použitie: `<BaseCard class="space-y-4"> … </BaseCard>`

### 1.7 BaseModal (`web/src/components/global/containers/BaseModal.vue`)

- **Účel:** fullscreen modal s backdropom.
- **Prop:** `modelValue`, `title`, `showClose`.
- **Sloty:** default, `title`, `footer`.
- **Emit:** `update:modelValue`, `close`.  
  Príklad:
  ```vue
  <BaseModal v-model="modalOpen" title="Nastavenia">
    … obsah …
    <template #footer>
      <BaseButton variant="secondary" @click="modalOpen = false">Zavrieť</BaseButton>
    </template>
  </BaseModal>
  ```

## 2. Globálne služby

### 2.1 SnackbarStack + useSnackbar (`web/src/components/global/feedback/SnackbarStack.vue`, `web/src/composables/useSnackbar.js`)

- `useSnackbar()` poskytuje metódy: `notify`, `dismiss`, `promote`, `pauseAll`, `resumeAll`.
- `notify` akceptuje buď string, alebo objekt `{ message, variant, duration }`.
- Varianty sú mapované na ikony (napr. `success` → zelená, `danger` → červená).
- SnackbarStack je pripojený v `App.vue`, takže stačí volať `useSnackbar().notify`.

## 3. Admin layout

### 3.1 AdminPageHeader (`web/src/components/layout/funkcionality/AdminPageHeader.vue`)

- **Účel:** horný panel s názvom podstránky, popisom a jedným akčným tlačidlom.
- **Prop:** `title` (povinné), `subtitle`, `actionLabel`, `actionIcon`.
- **Emit:** `action-click`.
- **Slot `actions`:** ak je potrebná vlastná sada tlačidiel.

### 3.2 AdminCategoryList (`web/src/components/layout/funkcionality/AdminCategoryList.vue`)

- **Účel:** pravý panel v správe menu, ktorý spravuje kategórie jedál.
- **Funkcionality:**
  - Zobrazenie zoznamu kategórií s ikonou, názvom a počtom položiek.
  - `v-model` vracia aktuálne vybranú kategóriu (`prop modelValue`, emit `update:modelValue`).
  - Kliknutím na „Pridať kategóriu“ sa otvorí interný modal.
  - Modal obsahuje:
    - Výber ikony (BaseSelect + živý náhľad FontAwesome).
    - Input pre názov (BaseInput).
    - Prepínač „Skryť / Zobraziť“ (BaseToggle).
    - Tlačidlo koša (BaseButton s ikonou `trash`).
  - Tlačidlo „Uložiť“ volá `useSnackbar().notify` a modal zatvorí.
- **Poznámka:** Zoznam ikon je definovaný v konštante `ICONS`. Pri rozšírení kategórií stačí doplniť záznam.

## 4. Web layout

### 4.1 WebHeader (`web/src/components/layout/header/WebHeader.vue`)

- Poskytuje navigáciu pre verejnú časť webu (desktop + mobil toggler).

### 4.2 WebFooter (`web/src/components/layout/footer/WebFooter.vue`)

- Obsahuje bloky „Linky“, „Kontakt“, „Firma“ + dolný pruh s navigáciou a ikonami (Instagram, e-mail, telefón).

### 4.3 NotFoundView (`web/src/views/web/NotFoundView.vue`)

- 404 stránka s vlastným dizajnom, CTA tlačidlami (`BaseButton`) a textom.  
  CTA smerujú na `/` (Späť k menu) a `/kontakt` (Kontaktovať obsluhu).

## 5. Stránky vo vybraných častiach

### 5.1 SpravaMenuView (`web/src/views/admin/SpravaMenuView.vue`)

- Používa `AdminPageHeader` a dvojstĺpcový layout (`BaseCard` + `AdminCategoryList`).
- Má BaseModal „Pridať nové jedlo“, ktorý je zatiaľ iba placeholder.

### 5.2 ZmenaZabudnutehoHeslaPoEmailiView (`web/src/views/admin/ZmenaZabudnutehoHeslaPoEmailiView.vue`)

- Jednoduchý formulár s dvomi BaseInputmi; pri odoslaní zobrazí snackbar a presmeruje na login.

### 5.3 App.vue

- Rozlišuje admin šablónu podľa `meta.requiresRole === 'admin'` (zobrazí `NavBarAdmin`, `AdminHeaderPanel`, `AdminFooter`).
- Pre webové stránky sa používa podmienka `showInMore`, ktorá je `true` ak trasa patrí do web sekcie (`meta.section === 'web'`) alebo má meta flag `useWebShell` (napr. `/admin/login`).
- `showInMore` zobrazuje `WebHeader` a `WebFooter`.
- V súčasnosti obsahuje testovací BaseModal (možno odstrániť po integrácii reálnych modalov).

## 6. Konvencie a odporúčania

1. **Stylovanie:** preferujeme Tailwind utility priamo v šablónach; ak je potreba kontajneru, použite `BaseCard`.
2. **Emity:** namiesto inline arrow funkcií používať menované metódy (`@update:modelValue="updateX(value)"`).
3. **Modaly:** otváranie/ zatváranie riadi rodič cez `v-model`; `BaseModal` už zabezpečuje backdrop a klik mimo.
4. **Snackbar:** pri každej akcii, ktorá sa končí notifikáciou (uloženie, chyba), volajte `useSnackbar().notify`.
5. **Vlastné komponenty:** keď vytvárate nové admin widgety (napr. iné panely), umiestňujte ich do `web/src/components/layout/funkcionality/` a dokumentujte ich API v tomto súbore.

Aktualizácia tohto dokumentu je nutná pri pridaní každej novej komponenty alebo zmeny API existujúcich komponentov.
