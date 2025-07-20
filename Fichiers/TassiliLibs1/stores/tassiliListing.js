import { defineStore } from 'pinia'

export const TassiliListing  = defineStore('listing', {
    state: () => ({ 
      settings: {}, 
      actionIds : [],
      groupActions : [],
      show : false,
      show2 : false,
      customActionModal: false,
      allFilters: {},
      customFilters: {},
      sessionFilter: {},
      urlCustomValidation : '',
      recordAction : {},
    }),
    getters: {
     
    },
    actions: {

      resetActionIds() {
        this.actionIds = []
      },

       AddActionIds(a) {

        this.show = false
        if (!this.actionIds.includes(a)) {
          this.actionIds.push(a);
        }
         if(this.actionIds.length == 1) {
        this.show2 = false
         }
      },

      RemoveActionIds(a) {
    
        this.show = false
        const index = this.actionIds.indexOf(a);
          if (index !== -1) {
                 this.actionIds.splice(index, 1);
                }
      },

    },
  }) 