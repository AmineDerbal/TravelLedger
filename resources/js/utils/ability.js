export const updateAbility = (ability, rules) => {
  ability.update(rules);
};

export const canEditOrDestroy = (action, subject, item, user, ability) => {
  const actionAll = action === 'edit_own' ? 'edit' : 'destroy';

  return (
    (ability.can(action, subject) && item.user.id === user.id) ||
    ability.can(actionAll, subject)
  );
};
