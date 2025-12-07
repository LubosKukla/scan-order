const defaultHours = [
  { day_of_week: 1, open_time: '', close_time: '', is_closed: true },
  { day_of_week: 2, open_time: '', close_time: '', is_closed: true },
  { day_of_week: 3, open_time: '', close_time: '', is_closed: true },
  { day_of_week: 4, open_time: '', close_time: '', is_closed: true },
  { day_of_week: 5, open_time: '', close_time: '', is_closed: true },
  { day_of_week: 6, open_time: '', close_time: '', is_closed: true },
  { day_of_week: 7, open_time: '', close_time: '', is_closed: true },
];

const state = () => ({
  defaultHours,
  openHours: [...defaultHours],
});

export default state;
