// Creating our tabs object
export const tabProps = [
  {
    id: 'reviews',
    name: 'Revisões',
    href: '#reviews',
  },
  {
    id: 'editions',
    name: 'Edições',
    href: '#editions',
  },
  {
    id: 'moderation',
    name: 'Moderação',
    href: '#moderation',
    hidden: true,
    locked: true,
  },
  {
    id: 'curatorship',
    name: 'Curadoria',
    href: '#curatorship',
    hidden: true,
    locked: true,
  },
];

export const getTabById = (id) => {
  // Getting the filtered items
  const result = tabProps.filter(tab => tab.id === id);

  if (result.length > 0) return result[0];
  return null;
};

