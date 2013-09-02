(($) ->

  window.nekland = {} if not window.nekland?
  window.nekland.lang = {} if not window.nekland.lang?
  window.nekland.lang.editor = {}

  window.nekland.lang.editor['en'] =
    swapToText: 'swap to text'
    swapToHtml: 'swap to html'
    italic: 'italic'
    bold: 'bold'

  ###
    Nekland Editor

    For options parameter, see documentation
    For templates parameter, all is function, see documentation
  ###

  $.fn.neklandEditor = (_options={},_templates={}) ->

    this.each ->
      $this = $ @
      editor = $this.data('nekland-editor')
      if !editor
        $this.data('nekland-editor', new NeklandEditor($this, _options, _templates))

  class NeklandEditor

    # Init all variables
    constructor: ($textarea, _options, _templates) ->

      self = @

      # Setting definition
      @settings = $.extend
        mode: 'classical'
        uid:  uniqid()
        lang: 'en'
      , _options

      @translations = window.nekland.lang.editor[@settings.lang]

      ###
        Templates definition.
        In this plugin, templates are simple functions.
      ###
      @templates = $.extend

        # Make buttons
        # takes an array of functions
        buttons: (buttons) ->
          tpl = '<div>'

          tpl += button() for button in buttons

          tpl += '</div>'
        classicalButtons: () ->
          tpl = '<button type="button" class="btn nekland-editor-command" data-editor-command="bold"><b>' + self.translate('bold', {ucfirst: true}) + '</b></button>'
          tpl += '<button type="button" class="btn nekland-editor-command" data-editor-command="italic"><i>' + self.translate('italic', {ucfirst: true}) + '</i></button>'

        # Main template, include others
        # The nekland-editor-html class is needed
        main: (buttons, size) ->
          tpl = buttons
          tpl += '<div class="nekland-editor-html" style="width:'+size[0]+'px;height:'+size[1]+'px" contenteditable="true"></div>'

        switchButton: (css_class) ->
          '<a href="#" class="' + css_class + '"></a>'


        # Load the whole templates
        load: ($element, uid) ->
          $wrapper = $ '<div>',
            id: 'nekland-editor-wrapper-' + uid

          # Wrap into a unique id element
          $element.wrap($wrapper)
          $element.before(@main(@buttons([@classicalButtons]), [$element.width(), $element.height()]))
          $element.after(@switchButton('nekland-switch-button'))
          $element.css('display', 'block').hide()


          $wrapper = $ '#nekland-editor-wrapper-' + uid

          if html = $element.html()
            $wrapper.find('.nekland-editor-html').html(html)
          else
            $wrapper.find('.nekland-editor-html').html('<p></p>')

          $wrapper.find('.nekland-switch-button').html(self.translate('swapToHtml', {ucfirst: true}))

          $wrapper

      , _templates


      # Getting wrapper by loading templates
      @$wrapper = @templates.load $textarea, @settings.uid


      # Getting original texarea & new field
      @$textarea = $textarea
      @$editor   = @$wrapper.find('.nekland-editor-html')

      # make lovely html
      @$editor = @$editor.html @p_ize(@$editor.html())

      @lastKey   = null

      @addEvents()
      #@setFocusNode(@$editor.find('p')[0])


    command: ($button) ->
      if @$editor.is ':visible'
        document.execCommand($button.data('editor-command'), false, $button.data('editor-command'))

      @synchronize()

      false

    # switch from textarea to editor
    # in both directions
    #
    switchEditor: ($switcher) ->
      if @$editor.is ':visible'
        # Notice: no need to synchronize since it's done on each keyup
        @$editor.hide()
        @$textarea.show()
        $switcher.html(@translate('swapToText', {ucfirst: true}))
      else
        @$editor.html(@$textarea.val())
        @$textarea.hide()
        @$editor.show()
        $switcher.html(@translate('swapToHtml', {ucfirst: true}))

      false

    addEvents: ->
      # Add switch event
      $switcher = @$wrapper.find('.nekland-switch-button')
      $switcher.click $.proxy(@switchEditor, @, $switcher)

      self = @
      # Add Command event
      @$wrapper.find('.nekland-editor-command').click ->
        self.command($(@))

      # Add events on keypress
      # later, man
      @$editor.keyup $.proxy(@onKeyUp, @)
      return

    onKeyUp: (event) ->
      @synchronize()

      return

    synchronize: ->
      @$textarea.val(@$editor.html())

    # Translate strings
    translate: (str, options={}) ->
      res = @translations[str]

      if options.ucfirst?
        res = res.charAt(0).toUpperCase() + res.slice(1);

      res


    ###
      DOM Manipulation:
    ###

    # Transform to paragraphed html
    p_ize: (str) ->
      str = $.trim str
      if str == '' or str == '<p></p>'
        return '<p><br /></p>'
      return str

    getSelection: ->
      if window.getSelection?
        window.getSelection()
      else if document.getSelection?
        document.getSelection()
      else
        document.selection.createRange()

    # cash from redactor
    getCurrentNode: () ->
      if window.getSelection?
        return @getSelectedNode().parentNode
      return

    setFocusNode: (node) ->
      range = document.createRange()
      selection = @getSelection()
      if selection != null
        selection.collapse node, 0
        selection.extend node, 0

      @$editor.trigger('focus')

    insertNodeAtCaret: (node) ->
      sel = @getSelection
      if window.getSelection
        if sel.rangeCount
          range = sel.getRangeAt(0)
          range.collapse(false)
          range.insertNode(node)
          range = range.cloneRange()
          range.selectNodeContents(node)
          range.collapse(false)
          sel.removeAllRanges()
          sel.addRange(range)



)(jQuery)

uniqid = (prefix, more_entropy) ->
  if typeof prefix == 'undefined'
    prefix = "";


  formatSeed =  (seed, reqWidth) ->
    seed = parseInt(seed, 10).toString(16) # to hex str

    if reqWidth < seed.length  # so long we split
      return seed.slice(seed.length - reqWidth)

    if reqWidth > seed.length # so short we pad
      return Array(1 + (reqWidth - seed.length)).join('0') + seed

    seed


  # BEGIN REDUNDANT
  if not this.php_js
    this.php_js = {}

  # END REDUNDANT
  if !this.php_js.uniqidSeed # init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15)

  this.php_js.uniqidSeed++

  retId = prefix; # start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8)
  retId += formatSeed(this.php_js.uniqidSeed, 5); # add seed hex string
  if more_entropy
    # for more entropy we add a float lower to 10
    retId += (Math.random() * 10).toFixed(8).toString()


  retId