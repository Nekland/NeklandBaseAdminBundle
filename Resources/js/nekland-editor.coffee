(($) ->

  window.nekland = {} if not window.nekland?
  window.nekland.lang = {} if not window.nekland.lang?
  window.nekland.lang.editor = {}

  window.nekland.lang.editor['en'] =
    swapToText: 'swap to text'
    swapToHtml: 'swap to html'
    italic: 'italic'
    bold: 'bold'
    addLink: 'add link'
    close: 'close'
    insertLink: 'insert link'
    link: 'link'
    removeLink: 'remove link'
    notALink:   'your link is not a valid link'

  ###
    Nekland Editor

    For options parameter, see documentation
    For templates parameter, all is function, see documentation

    TODO:
      -> Handle copy/paste process
      -> Add image/link/etc support
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

        linkButton: ->
          "<div class=\"btn-group\">
            <a class=\"btn dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">
              " + self.translate('link', {ucfirst: true}) + "
            <span class=\"caret\"></span>
            </a>
            <ul class=\"dropdown-menu\">
              <li>
                <a href=\"#\" class=\"open-link-modal\">
                  " + self.translate('insertLink', {ucfirst: true}) + "
                </a>
              </li>
              <li>
                <a href=\"#\" class=\"nekland-editor-command\" data-editor-command=\"unlink\" data-prevent=\"no\">
                  " + self.translate('removeLink', {ucfirst: true}) + "
                </a>
              </li>
            </ul>
          </div>"

        # Main template, include others
        # The nekland-editor-html class is needed
        main: (buttons, size) ->
          tpl = buttons
          tpl += '<div class="nekland-editor-html" style="width:'+size[0]+'px;height:'+size[1]+'px" contenteditable="true"></div>'

        switchButton: (css_class) ->
          '<a href="#" class="' + css_class + '"></a>'

        modals: ->
          "<div class=\"modal hide fade nekland-editor-link\" role=\"dialog\" aria-hidden=\"true\">
            <div class=\"modal-header\">
              <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
              <h3>" + self.translate('addLink', {ucfirst: true}) + "</h3>
            </div>
            <div class=\"modal-body\">
              <input type=\"text\" class=\"link-input\" style=\"width: 250px;\" />
              <p class=\"error link-error\"></p>
            </div>
            <div class=\"modal-footer\">
              <button type=\"button\" class=\"btn\" data-dismiss=\"modal\" aria-hidden=\"true\">" + self.translate('close', {ucfirst: true}) + "</button>
              <button type=\"button\" class=\"btn btn-primary nekland-editor-command\" data-dismiss=\"modal\"
                      data-option-selector=\".link-input\" data-editor-command=\"createLink\"
                      data-prevent=\"no\">" +
                self.translate('insertLink', {ucfirst: true}) + "
              </button>
            </div>
          </div>"


        # Load the whole templates
        load: ($element, uid) ->
          $wrapper = $ '<div>',
            id: 'nekland-editor-wrapper-' + uid

          # Wrap into a unique id element
          $element.wrap($wrapper)
          $element.before(@main(@buttons([@classicalButtons, @linkButton]), [$element.width(), $element.height()]))
          $element.after(@switchButton('nekland-switch-button'))
          $element.css('display', 'block').hide()


          $wrapper = $ '#nekland-editor-wrapper-' + uid

          if html = $element.html()
            $wrapper.find('.nekland-editor-html').html(html)
          else
            $wrapper.find('.nekland-editor-html').html('<p></p>')

          $wrapper.append(@modals())

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

      option  = null
      command = $button.data('editor-command')

      if option = $button.data('option-selector')
        option = @$wrapper.find(option).val()

      if command == 'createLink'
        # link check
        if not /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(option)
          @$wrapper.find('.link-error').html(@translate('notALink', {ucfirst: true}))
          return false

      else if command == 'unlink'
        node = @getCurrentNode()
        if node.tagName == 'A'
          $(node).replaceWith($(node).text())
          @synchronize()
          return



        # replacing selection
        @replaceSelection()


      if @$editor.is ':visible'
        #console.log 'execute: ' + $button.data('editor-command') + ' with ' + option
        document.execCommand(command, false, option)

      @synchronize()

      if prevent = $button.data('prevent')
        if prevent == 'no'
          return true

      false

    # switch from textarea to editor
    # in both directions
    #
    switchEditor: ($switcher) ->
      if @$editor.is ':visible'
        # Notice: no need to synchronize since it's done on each keyup
        @$editor.hide()
        @$textarea.val(@clearHtml(@$textarea.val()))
        @$textarea.show()
        $switcher.html(@translate('swapToText', {ucfirst: true}))
      else
        @$editor.html(@clearHtml(@$textarea.val()))
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

      @$wrapper.find('.open-link-modal').click $.proxy( ->
        @saveSelection()
        @$wrapper.find('.nekland-editor-link').modal('show')
      , @)

      @$wrapper.find('.link-input').keydown @removeEnter
      return

    onKeyUp: (event) ->
      @synchronize()

      return

    synchronize: ->
      @$textarea.val(@$editor.html())

    # Translate strings
    translate: (str, options={}) ->
      if @translations[str]?
        res = @translations[str]
      else
        throw new Error('Translation missing')

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

    setSelection: (orgn, orgo, focn, foco) ->
      if focn == null
        focn = orgn

      if foco == null
        foco = orgo

      sel = @getSelection();

      if not sel
        return

      if sel.collapse && sel.extend
        sel.collapse(orgn, orgo)
        sel.extend(focn, foco)

      else # IE9
        r = document.createRange()
        r.setStart(orgn, orgo)
        r.setEnd(focn, foco)

        try
          sel.removeAllRanges()

        sel.addRange(r)

    # cash from redactor
    getCurrentNode: () ->
      if window.getSelection?
        return @getSelectedNode().parentNode
      return

    getParentNode: ->
      $(@getCurrentNode()).parent()[0]

    getSelectedNode: ->
      if window.getSelection?
        s = window.getSelection()
        if s.rangeCount > 0
          return @getSelection().getRangeAt(0).commonAncestorContainer
        else
          return false
      else if document.selection?
        return @getSelection()

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

    replaceSelection: () ->
      if @savedSel? && @savedSelObj? && @savedSel[0].tagName != 'BODY'
        if $(@savedSel[0]).closest('.nekland-editor-html').size() == 0
          @$editor.focus()
        else
          @setSelection(@savedSel[0], @savedSel[1], @savedSelObj[0], @savedSelObj[1])
      else
        @$editor.focus()


    saveSelection: ->
      @$editor.focus()
      @savedSel    = @getOrigin()
      @savedSelObj = @getFocus()

    getOrigin: ->
      if not ((sel = @getSelection()) && (sel.anchorNode != null))
        return null

      [sel.anchorNode, sel.anchorOffset]

    getFocus: ->
      if not ((sel = @getSelection()) && (sel.focusNode != null))
        return null

      [sel.focusNode, sel.focusOffset];

    removeEnter: (e) ->
      if e.which == 13
        e.preventDefault()

    clearHtml: (html) ->
      html.replace(/&nbsp;/g, ' ', html)


  return
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
