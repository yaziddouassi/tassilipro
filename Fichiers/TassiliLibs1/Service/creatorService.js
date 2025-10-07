import { usePage } from '@inertiajs/vue3'
import { TassiliRoutes } from '@/Vendor/TassiliLibs/stores/tassiliRoutes'
import {TassiliInput}    from '@/Vendor/TassiliLibs/stores/tassiliInput'
import { router } from '@inertiajs/vue3';
import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

export function creatorService() {
  
   const page = usePage()
   const tassiliroutes  = TassiliRoutes();
   const tassiliInput = TassiliInput();

  function initForm() {
 

   tassiliroutes.setRoutes(page.props.tassiliSettings.routes,page.props.tassiliSettings.tassiliPanel)
   tassiliInput.form = page.props.tassiliSettings.tassiliFields
   tassiliInput.formStart = JSON.parse(JSON.stringify(page.props.tassiliSettings.tassiliFields))
   tassiliInput.isAnimated = 'off'

   let currentRoute = tassiliroutes.routes.find(item => item.model === page.props.tassiliSettings.tassiliModelClassName)?.route;
   if(currentRoute == undefined) {
       currentRoute = page.props.tassiliSettings.tassiliDataRouteListe
    }


  }


  function cleanQuillContent(html) {
  if (typeof html !== 'string') return html;
  return html.replace(/<p>\s*<\/p>/g, '').replace(/<p><br><\/p>/g, '').trim();
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

    tassiliInput.form = JSON.parse(JSON.stringify(page.props.tassiliSettings.tassiliFields))
   
  
}


function insert(action) {
 
  
  const formData = new FormData();
  
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



const tab3 = ['Text', 'Date', 'Hidden', 'Select', 'Number', 'Radio', 'Checkbox', 'CheckboxList', 'Password', 'Textarea','Signature'];
  if (tab3.includes(tassiliInput.form[key]['type'])) {
    formData.append(key , tassiliInput.form[key]['value']);
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
       tassiliInput.isAnimated = 'off'
      tassiliInput.setError(errors);
      console.error('Validation Errors:', tassiliInput.errors);
    },
    onSuccess: () => {
      if (action === 'creer') {
         tassiliInput.isAnimated = 'off'
        afterCreate1();
      } else if (action === 'other') {
         tassiliInput.isAnimated = 'off'
        afterCreate2();
      }
    }
  });



}



  
 function submit(action) {
   tassiliInput.isAnimated = 'on'
   insert(action);
  }


  return {
    initForm,
    submit
  }
}