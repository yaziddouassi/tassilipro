import { defineStore } from 'pinia'
import { router } from '@inertiajs/vue3';

export const TassiliRoutes = defineStore('routes', {
  state: () => ({
    routes: {},
    
  }),
  
  actions: {
    
    setRoutes(a , panel) {
      // 🔧 Copie profonde pour ne pas modifier props.routes
     let b = JSON.parse(JSON.stringify(a))
     const panelStorage = 'tassili.' + panel
    
      if (!localStorage.getItem(panelStorage)) {
        localStorage.setItem(panelStorage, JSON.stringify({}))
      }

      const tassili = JSON.parse(localStorage.getItem(panelStorage))

      Object.keys(b).forEach((key) => {
        const model = b[key]['model']
        if (!tassili.hasOwnProperty(model)) {
          tassili[model] = {}
          localStorage.setItem(panelStorage, JSON.stringify(tassili))
        }
      })

      const tassili2 = JSON.parse(localStorage.getItem(panelStorage))

      Object.keys(b).forEach((key) => {
        const model = b[key]['model']
        const temp = b[key]['route']
        let temp2 = ''

        Object.keys(tassili2[model]).forEach((cle) => {
          if (tassili2[model][cle] != '') {
            if (temp2 === '') {
              temp2 += '?' + cle + '=' + tassili2[model][cle]
            } else {
              temp2 += '&' + cle + '=' + tassili2[model][cle]
            }
          }
        })

      
        b[key]['route'] = temp + temp2
      })

      this.routes = b 
      
    },

     visit(panel,model,defaultPath) {
  
      const panelStorage = 'tassili.' + panel
      const defaultChemin = '/' + panel + '/' + defaultPath
      
      if (!localStorage.getItem(panelStorage)) {
          return router.get(defaultChemin)
      } 

       if (localStorage.getItem(panelStorage)) {
       const tassili = JSON.parse(localStorage.getItem(panelStorage))

        if(!tassili[model]) {
           return router.get(defaultChemin)
        }

        let finalPath = defaultChemin
        let temp = ''
        if(tassili[model]) {
            Object.keys(tassili[model]).forEach((cle) => {
                if (tassili[model][cle] != '') {
                   if (temp === '') {
                     temp += '?' + cle + '=' + tassili[model][cle]
                   } else {
                   temp += '&' + cle + '=' + tassili[model][cle]
                  }
                  }
                })
          finalPath =  finalPath + temp

         return router.get(finalPath)  
        }

      }
    },
     


  },
})