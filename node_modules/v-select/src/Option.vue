<template>
  <li @mousedown.prevent="chooseOption(value)"
      class="m-select-option"
      :class="{'selected': selected || pick}"
      :value="value">
    <slot></slot>
  </li>
</template>
<script type="text/babel">
  export default{
    data () {
      return {
        // 用户上下箭头选中,会在父组件中直接操作
        pick: false
      }
    },

    props: {
      value: [String, Number, Object],

      selected: {
        type: Boolean,
        default: false
      }
    },

    methods: {
      chooseOption (value) {
        this.$parent.$emit('selected', value)
      }
    }
  }
</script>
