<template>
  <div class="m-select" :class="{'disabled': disabled}" @click.stop>
    <label class="m-select-label">
      <input
        ref="input"
        class="m-select-input"
        :disabled="disabled"
        :readonly="readonly"
        :placeholder="placeholder"
        :value="value"
        @keydown.down.prevent="navigateOptions('next')"
        @keydown.up.prevent="navigateOptions('prev')"
        @keydown.enter.prevent="selectOption"
        @input="onInput"
        @click="openSelect"
        @blur="closeSelect">
    </label>
    <div v-show="this.size > 0 && open"
         class="m-option-box"
         :style="{maxHeight: this.maxColumns * this.columnHeight + 24 + 'px'}">
      <div
        ref="scrollBar"
        class="m-option"
        :style="{maxHeight: this.maxColumns * this.columnHeight + 'px'}"
        @mousedown.prevent="chooseOption">
        <ul class="m-option-group">
          <slot></slot>
        </ul>
      </div>
    </div>
    <slot name="nodata"></slot>
  </div>
</template>
<script type="text/babel">
  const UA = window.navigator.userAgent.toLowerCase()
  const isIE = UA && /msie|trident/.test(UA)
  export default{
    props: {
      placeholder: {
        type: String
      },

      // 是否可选
      disabled: {
        type: Boolean,
        default: false
      },

      // 是否是只读
      readonly: {
        type: Boolean,
        default: false
      },

      // 下拉列表的个数
      size: Number,

      value: [String, Number, Object],

      maxColumns: {
        type: Number,
        default: 5
      },

      columnHeight: {
        type: Number,
        default: 48
      }
    },

    data () {
      return {
        // 打开下拉框
        open: false,
        // 选中下拉项的下表
        optionIndex: -1
      }
    },

    methods: {
      onInput (e) {
        this.$emit('input', e.target.value)
        this.open = true
      },

      openSelect () {
        if (!this.disabled) {
          let hasMatching = this.$children.length > 0
          if (this.readonly) {
            this.open = true
          } else if (this.value && hasMatching) {
            this.open = true
          }
        }
      },

      // 关闭下拉框
      // 关闭方式,1:失去光标,2:选中下拉列表
      // 失去光标需要校验现在填写的值是否在下拉列表中存在
      closeSelect (e) {
        // ie兼容性
        if (this.cancelBlur) {
          this.cancelBlur = false
          return
        }
        if (!this.disabled) {
          this.open = false
        }
        // 对键盘事件
        this.optionIndex = -1
      },

      chooseOption (e) {
        // 当出现滚动条时, 点击滚动条不消失
        if (e.target.tagName.toUpperCase() === 'DIV') {
          if (isIE) {
            this.cancelBlur = true
          }
          return false
        }
        // 在失去焦点自动触发回调
        if (this.$refs.input) {
          this.$refs.input.blur()
        }

        if (isIE) {
          this.cancelBlur = false
          this.closeSelect()
        }
      },

      // 通过enter键选择
      selectOption () {
        if (this.$children && this.optionIndex > -1) {
          const vm = this.$children[this.optionIndex]
          this.closeSelect()
          this.$emit('selected', vm.value)
        }
      },

      // 通过上下箭头操作下拉框
      navigateOptions (dir) {
        if (!this.open) return
        const options = this.$children
        if (options.length === 0) {
          return
        }
        // 这里直接操作了子元素的数据是不建议的，但没有简单办法
        options.forEach((vm) => {
          vm.pick = false
        })
        if (dir === 'next') {
          if (options.length - 1 > this.optionIndex) {
            this.optionIndex++
          }
        } else if (this.optionIndex > 0) {
          this.optionIndex--
        }
        options[this.optionIndex].pick = true
        this.$refs.scrollBar.scrollTop = this.optionIndex * this.columnHeight
      }
    },

    mounted () {
      if (isIE) {
        document.addEventListener('click', (e) => {
          this.cancelBlur = false
          this.closeSelect()
        }, true)
      }
    }
  }
</script>

<style lang="stylus" rel="stylesheet/stylus">
  .m-select {
    position: relative
    width 100%
    height 100%
    &.disabled {
      .m-select-input {
        cursor not-allowed
      }
    }
    .m-select-label,
    .m-select-input {
      display block
      width 100%
      height 100%
    }
    .m-select-input {
      position absolute
      padding 0 16px
      box-sizing border-box
      border 1px solid #e5e5e5
      appearance none
      background-color #ffffff
      background-image none
      color #1f2d3d
      font-size inherit
      outline none
      &:focus {
        border 1px solid #316ccb
        z-index 1
      }
    }

    .m-option-box {
      position absolute
      z-index 5
      top 32px
      width 100%
      padding 12px 0
      background #ffffff
      border 1px solid #e5e5e5
      box-shadow 0 0 16px 0 rgba(197, 197, 197, 0.50)
    }
    .m-option {
      overflow auto

      .m-option-group {
        list-style none
        padding 0
        margin 0
      }

      .m-select-option {
        font-size 12px
        color #656565
        cursor pointer
        &:hover,
        &:focus,
        &.selected {
          color #316ccb
        }
        &:hover,
        &:focus {
          background-color #fcfcfc
        }
      }
    }
  }

  @css {
    ::-webkit-input-placeholder {
      color: #c5c5c5;
    }
    :-moz-placeholder {
      　　color: #c5c5c5;
    }
    ::-moz-placeholder {
      　　color: #c5c5c5;
    }
    :-ms-input-placeholder {
      　　color: #c5c5c5;
    }
    .u-light::-webkit-input-placeholder {
      color: #959595;
    }
    .u-light:-moz-placeholder {
      　　color: #959595;
    }
    .u-light::-moz-placeholder {
      　　color: #959595;
    }
    .u-light:-ms-input-placeholder {
      　　color: #959595;
    }
  }
</style>
