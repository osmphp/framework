import macaw from 'Osm_Framework_Js/vars/macaw';
import Form from './Form';
import StringField from './StringField';
import PriceField from './PriceField';
import DateField from './DateField';
import PasswordField from './PasswordField';
import DropdownField from './DropdownField';
import TextField from './TextField';
import CheckboxField from './CheckboxField';
import ImageField from './ImageField';

macaw.controller('.form', Form);
macaw.controller('.field.-string', StringField);
macaw.controller('.field.-price', PriceField);
macaw.controller('.field.-date', DateField);
macaw.controller('.field.-password', PasswordField);
macaw.controller('.field.-dropdown', DropdownField);
macaw.controller('.field.-text', TextField);
macaw.controller('.field.-checkbox', CheckboxField);
macaw.controller('.form-section.-image', ImageField);

