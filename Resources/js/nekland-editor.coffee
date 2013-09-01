(($) ->

  ###
    Nekland Editor

    For options parameter, see documentation
    For templates parameter, all is function, see documentation
  ###

  $.fn.neklandEditor = (_options={},_templates={}) ->
    ###
      Settings:

      -> "uid" is an unique id used to show multiple editors on a page
    ###
    settings = $.extend
      mode: 'classical'
      uid: uniqid()
    , _options


    ###
      Templates definition.
      In this plugin, templates are simple functions.
    ###
    templates = $.extend
      ###
        Make buttons
        takes an array of functions
      ###
      buttons: (buttons) ->
        tpl = '<div>'

        tpl += button() for button in buttons

        tpl += '</div>'
      classicalButtons: () ->
        '<button type="button" class="btn nekland-editor-command" data-editor-command="bold"><b>Bold</b></button>'
      ###
        Main template, include others
        The nekland-editor-html class is needed
      ###
      main: (buttons, size) ->
        tpl = buttons
        tpl += '<div class="nekland-editor-html" style="width:'+size[0]+'px;height:'+size[1]+'px" contenteditable></div>'

      switchButton: (css_class) ->
        '<a href="#" class="' + css_class + '">Switch</a>'

      ###
        Load the whole templates
      ###
      load: ($element, uid) ->
        $wrapper = $ '<div>',
          id: 'nekland-editor-wrapper-' + uid

        # Wrap into a unique id element
        $element.wrap($wrapper)
        $element.before(@main(@buttons([@classicalButtons]), [$element.width(), $element.height()]))
        $element.after(@switchButton('nekland-switch-button'))
        $element.css('display', 'block').hide()


        $wrapper = $ '#nekland-editor-wrapper-' + uid

        $wrapper.find('.nekland-editor-html').html($element.html())


        $wrapper



    , _templates







    class NeklandEditor
      constructor: ($textarea) ->
        # Getting wrapper by loading templates
        @$wrapper = templates.load $textarea, settings.uid


        # Getting original texarea & new field
        @$textarea = $textarea
        @$editor  = @$wrapper.find('.nekland-editor-html')

        # Add switch event
        @$wrapper.find('.nekland-switch-button').click @switchEditor.bind @

        self = @
        # Add Command event
        @$wrapper.find('.nekland-editor-command').click ->
          self.command($(@))



      command: ($button) ->
        if @$editor.is ':visible'
          document.execCommand($button.data('editor-command'), false, $button.data('editor-command'))

        false

      # switch from textarea to editor
      # in both directions
      #
      switchEditor:  ->
        if @$editor.is ':visible'
          @$textarea.html(@$editor.html())
          @$editor.hide()
          @$textarea.show()
        else
          @$editor.html(@$textarea.val())
          @$textarea.hide()
          @$editor.show()

        false



    new NeklandEditor(@)

    @

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