import macaw from 'Manadev_Framework_Js/vars/macaw';

import Hidden from './Hidden';
import Role from './Role';

macaw.controller('.icon', Hidden);
macaw.controller('.menu__item', Role, {role: 'menuitem'});
