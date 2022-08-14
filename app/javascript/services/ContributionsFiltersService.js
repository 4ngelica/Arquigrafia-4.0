/**
 * Here we're going to define the filter items accepted on Contributions page
 */
export const filterItems = [
  {
    id: 'all',
    name: 'Todas',
  },
  {
    id: 'accepted',
    name: 'Aceitas',
  },
  {
    id: 'rejected',
    name: 'Recusadas',
  },
  {
    id: 'waiting',
    name: 'Aguardando',
  },
];

export const getFilterById = (id) => {
  // Filtering items
  const result = filterItems.filter(filterItem => filterItem.id === id);

  if (result.length > 0) return result[0];
  return null;
};

