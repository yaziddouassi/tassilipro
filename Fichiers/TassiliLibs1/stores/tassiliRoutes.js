import { defineStore } from 'pinia'

export const TassiliRoutes = defineStore('routes', {
  state: () => ({
    routes: {},
  }),

  actions: {
    setRoutes(a, panel) {
      if (typeof window === 'undefined') return // 🔒 Skip in SSR

      // Copie profonde pour éviter les effets de bord
      let b = JSON.parse(JSON.stringify(a))
      const panelStorage = 'tassili.' + panel

      // Initialise le stockage s’il n’existe pas
      if (!localStorage.getItem(panelStorage)) {
        localStorage.setItem(panelStorage, JSON.stringify({}))
      }

      const tassili = JSON.parse(localStorage.getItem(panelStorage))

      // Assure que chaque modèle ait une clé dans le stockage
      Object.keys(b).forEach((key) => {
        const model = b[key]['model']
        if (!tassili.hasOwnProperty(model)) {
          tassili[model] = {}
          localStorage.setItem(panelStorage, JSON.stringify(tassili))
        }
      })

      const tassili2 = JSON.parse(localStorage.getItem(panelStorage))

      // Concatène les filtres dans l’URL
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

    addFilter() {
      // à implémenter plus tard
    },
  },
})