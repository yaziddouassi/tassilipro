import { usePage } from '@inertiajs/vue3'
import { TassiliRoutes } from '@/Vendor/TassiliLibs/stores/tassiliRoutes'
import {TassiliInput}    from '@/Vendor/TassiliLibs/stores/tassiliInput'
import { router } from '@inertiajs/vue3';

export function updatorService() {

   const page = usePage()
   const tassiliroutes  = TassiliRoutes();
   const tassiliInput = TassiliInput();
  function initForm() {
  

   tassiliroutes.setRoutes(page.props.routes,page.props.tassiliPanel)
   tassiliInput.form = page.props.tassiliFields


    let currentRoute = tassiliroutes.routes.find(item => item.model === page.props.tassiliSettings.tassiliModelClassName)?.route;
   if(currentRoute == undefined) {
       currentRoute = page.props.tassiliSettings.tassiliDataRouteListe
    }


    console.log(currentRoute)
  }


  function cleanQuillContent(html) {
  if (typeof html !== 'string') return html;
  return html.replace(/<p>\s*<\/p>/g, '').replace(/<p><br><\/p>/g, '').trim();
}



function checkNullable() {
  let temoin = 0;

  Object.keys(tassiliInput.form).forEach((champ) => {

    if (tassiliInput.form[champ]['type'] === 'MultipleFileEdit') {
        if (tassiliInput.form[champ]['options']['existingFiles'].length  === 0 && 
          tassiliInput.form[champ]['options']['tempUrlTabs'].length === 0 &&
      tassiliInput.form[champ]['options']['nullable'] === 'yes') {
         temoin++;
           }
    }
 
  });

  return temoin;
}


function update() {

const temoin = checkNullable();

if (temoin > 0) {
  const notyf = new Notyf({ position: { x: 'right', y: 'top' } });
  notyf.error(`${temoin} fields(s) required are missing(s).`);
  return;
}

const formData = new FormData();
  
formData.append('id' , page.props.tassiliRecordInput['id']);

  Object.keys(tassiliInput.form).forEach((key) => {


const tab1 = ['MultipleFileEdit']
    if (tab1.includes(tassiliInput.form[key]['type'])) {
    if(!tassiliInput.form[key]['value'] || tassiliInput.form[key]['value'].length === 0) {
      formData.append(key, '');
    }
    else if (Array.isArray(tassiliInput.form[key]['value'])) {
      tassiliInput.form[key]['value'].forEach((file, index) => {
        formData.append(`${key}[]`, file);
      });
    }
 

     const  temp = tassiliInput.form[key]['options']['existingFiles'] || '[]';
     const index = key + '_newtab'
      formData.append(index, JSON.stringify(temp));

    } 


const tab2 = ['FileEdit'];
    if(tab2.includes(tassiliInput.form[key]['type'])) {
      formData.append(key , tassiliInput.form[key]['value']);
    }


const tab3 = ['Text', 'Date', 'Hidden', 'Select', 'Number', 'Radio', 'Checkbox', 'CheckboxList', 'Password', 'Textarea'];
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
      tassiliInput.setError(errors);
      console.error('Validation Errors:', tassiliInput.errors);
    },
    onSuccess: () => {

      const notyf = new Notyf({ position: { x: 'right', y: 'top' } });
      notyf.success('Record Updated');

       let currentRoute = tassiliroutes.routes.find(item => item.model === page.props.tassiliSettings.tassiliModelClassName)?.route;
   if(currentRoute == undefined) {
       currentRoute = page.props.tassiliSettings.tassiliDataRouteListe
    }

      router.get(currentRoute);
     
      
    }
  });


}


  return {
    initForm,
    update
  }
}