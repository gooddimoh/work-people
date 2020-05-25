/**
 * Author: haiflive
 * src project: https://jsfiddle.net/sodamH/zwtLsqqn/?utm_source=website&utm_medium=embed&utm_campaign=zwtLsqqn
 */
Vue.component('nice-select', {
  props: ['options', 'value'],
  // template: '#niceSelect-template',
  mounted: function () {
    var vm = this
    
    $(this.$el)
      // init select2
      .niceSelect({ data: this.options })
      .val(this.value)
      .trigger('change')
      // emit event on change.
      .on('change', function () {
        vm.$emit('input', this.value)
      });
      
      $(this.$el).val(vm.value).trigger('change');
      $(this.$el).niceSelect('update');
  },
  watch: {
    value: function (value) {
      // update value
      $(this.$el).val(value).trigger('change');
    },
    options: function (options) {
      // update options
      $(this.$el).empty().niceSelect({ data: options })
    }
  },
  destroyed: function () {
    $(this.$el).off().niceSelect('destroy')
  },
  template: '<select><slot></slot></select>'
})
