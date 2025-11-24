// Icons pre ingrediencie a voliteľné možnosti jedál
import {
  faCheese,
  faBacon,
  faDrumstickBite,
  faFish,
  faSeedling,
  faPepperHot,
  faCarrot,
  faAppleWhole,
  faBreadSlice,
  faEgg,
  faBowlFood,
  faBowlRice,
  faLeaf,
  faMartiniGlassCitrus,
  faGlassWater,
  faCookie,
} from '@fortawesome/free-solid-svg-icons';

export const INGREDIENT_ICONS = {
  onion: { label: 'Cibuľa', icon: faLeaf },
  garlic: { label: 'Cesnak', icon: faLeaf },
  cheese: { label: 'Syr', icon: faCheese },
  bacon: { label: 'Slanina', icon: faBacon },
  beef: { label: 'Hovädzie', icon: faDrumstickBite },
  chicken: { label: 'Kuracie', icon: faDrumstickBite },
  fish: { label: 'Ryba', icon: faFish },
  shrimp: { label: 'Krevety', icon: faFish },
  egg: { label: 'Vajce', icon: faEgg },
  mushroom: { label: 'Huby', icon: faBowlFood },
  lettuce: { label: 'Šalát', icon: faLeaf },
  tomato: { label: 'Paradajka', icon: faAppleWhole },
  pepper: { label: 'Paprika', icon: faPepperHot },
  chili: { label: 'Chilli', icon: faPepperHot },
  carrot: { label: 'Mrkva', icon: faCarrot },
  potato: { label: 'Zemiak', icon: faBowlRice },
  olive: { label: 'Olivy', icon: faLeaf },
  lemon: { label: 'Citrón', icon: faMartiniGlassCitrus },
  herbs: { label: 'Bylinky', icon: faSeedling },
  spicy: { label: 'Pikantné', icon: faPepperHot },
  nuts: { label: 'Orechy', icon: faCookie },
  gluten: { label: 'Glutén', icon: faBreadSlice },
  vegan: { label: 'Vegan', icon: faSeedling },
  dairyFree: { label: 'Bez laktózy', icon: faGlassWater },
  glutenFree: { label: 'Bez lepku', icon: faSeedling },
};

export const INGREDIENT_ICON_OPTIONS = Object.entries(INGREDIENT_ICONS).map(([key, value]) => ({
  key,
  label: value.label,
  icon: value.icon,
}));

export function resolveIngredientIcon(key) {
  return INGREDIENT_ICONS[key]?.icon || faSeedling;
}
