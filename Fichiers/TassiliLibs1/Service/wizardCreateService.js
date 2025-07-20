import { usePage } from '@inertiajs/vue3'
import { TassiliRoutes } from '@/Vendor/TassiliLibs/stores/tassiliRoutes'
import {TassiliInput}    from '@/Vendor/TassiliLibs/stores/tassiliInput'
import { router } from '@inertiajs/vue3';

export function wizardCreateService() {
  
   const page = usePage()
   const tassiliroutes  = TassiliRoutes();
   const tassiliInput = TassiliInput();

  function initForm() {
 

   tassiliroutes.setRoutes(page.props.routes,page.props.tassiliPanel)
   tassiliInput.form = page.props.tassiliFields
   tassiliInput.formStart = JSON.parse(JSON.stringify(page.props.tassiliFields))
   tassiliInput.wizardInfo = JSON.parse(JSON.stringify(page.props.tassiliWizardInfo))
   tassiliInput.wizardCurrent = 1

   let currentRoute = tassiliroutes.routes.find(item => item.model === page.props.tassiliSettings.tassiliModelClassName)?.route;
   if(currentRoute == undefined) {
       currentRoute = page.props.tassiliSettings.tassiliDataRouteListe
    }

   
  }


  function cleanQuillContent(html) {
  if (typeof html !== 'string') return html;
  return html.replace(/<p>\s*<\/p>/g, '').replace(/<p><br><\/p>/g, '').trim();
}


function reculer() {
  tassiliInput.wizardCurrent =  tassiliInput.wizardCurrent - 1
}


function nextValidate() {
  tassiliInput.wizardCurrent =  tassiliInput.wizardCurrent + 1
}

function afterCreate1() {
  const notyf = new Notyf({ position: { x: 'right', y: 'top' } });
  notyf.success('Record created');
  let currentRoute = tassiliroutes.routes.find(item => item.model === page.props.tassiliSettings.tassiliModelClassName)?.route;
   if(currentRoute == undefined) {
       currentRoute = page.props.tassiliSettings.tassiliDataRouteListe
    }

  router.get(currentRoute);  
}


function afterCreate2() {
  const notyf = new Notyf({ position: { x: 'right', y: 'top' } });
  notyf.success('Record created Other');
   tassiliInput.resetError();

  tassiliInput.form  =   tassiliInput.formStart
  tassiliInput.wizardCurrent = 1
}


function insert(action) {
 
 

  const formData = new FormData();

   if (action == 'next') {
    formData.append('tassiliSaveActive', 'no');
  }
  if (action != 'next') {
    formData.append('tassiliSaveActive', 'yes');
  }
  
  formData.append('tassiliWizardStep',tassiliInput.wizardCurrent);


  Object.keys(tassiliInput.form).forEach((key) => {


const tab1 = ['MultipleFile'];
    if (tab1.includes(tassiliInput.form[key]['type'])) {
     
    if(!tassiliInput.form[key]['value'] || tassiliInput.form[key]['value'].length === 0) {
      formData.append(key, '');
    }
      
    else if (Array.isArray(tassiliInput.form[key]['value'])) {
      tassiliInput.form[key]['value'].forEach((file, index) => {
        formData.append(`${key}[]`, file);
      });
    }
    }


   const tab2 = ['File'];
    if(tab2.includes(tassiliInput.form[key]['type'])) {
      formData.append(key , tassiliInput.form[key]['value']);
    }


const tab3 = ['Text', 'Date', 'Hidden', 'Select', 'Number', 'Radio', 'Checkbox', 'CheckboxList', 'Password', 'Textarea'];
  if (tab3.includes(tassiliInput.form[key]['type'])) {
    formData.append(  key , tassiliInput.form[key]['value']);
    console.log(tassiliInput.form[key]['value'])
  }


const tab4 = ['Quill'];
    if(tab4.includes(tassiliInput.form[key]['type'])) {
      formData.append(key, cleanQuillContent(tassiliInput.form[key]['value'] || ''));
    }


 if (tassiliInput.form[key]['type'] === 'Repeater' ) {

   tassiliInput.form[key]['value'].forEach((item, i) => {
    Object.entries(item).forEach(([subKey, subValue]) => {

       if(tassiliInput.form[key]['fields'][subKey]['type'] === 'Quill') {
          subValue  = cleanQuillContent(subValue || '')
      }

      formData.append(`${key}[${i}][${subKey}]`, subValue);

    });
  });


}   




});
   



router.post(page.props.tassiliSettings.tassiliValidationUrl, formData, {
    forceFormData: true,
    onError: (errors) => {
      tassiliInput.setError(errors);
      console.error('Validation Errors:', tassiliInput.errors);
    },
    onSuccess: () => {
       if (action === 'creer') {
        afterCreate1();
      } else if (action === 'other') {
        afterCreate2();
      }
     else if (action === 'next') {
       nextValidate();
       tassiliInput.resetError();
      }
    }
  });





}
  
  
 function submit(action) {
   insert(action);
  }


  return {
    initForm,
    submit,
    reculer
  }
}