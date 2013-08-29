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
        '<button class="btn"><b>Bold</b></button>'
      ###
        Main template, include others
        The nekland-editor-html class is needed
      ###
      main: (buttons, size) ->
        tpl = buttons
        tpl += '<div class="nekland-editor-html" style="width:'+size[0]+'px;height:'+size[1]+'px" contenteditable></div>'
        tpl += '<a href="#" class="nekland-switch-button">Switch</a>'

      ###
        Load the whole templates
      ###
      load: ($element, uid) ->
        $wrapper = $ '<div>',
          id: 'nekland-eiditor-wrapper-' + uid

        # Wrap into a unique id element
        $element.wrap($wrapper);
        $element.after(@main(@buttons([@classicalButtons]), [$element.width(), $element.height()]))
        $element.hide()


        console.log $wrapper.find('.nekland-switch-button')[0]
        $wrapper.find('.nekland-switch-button').click () ->
          switchEditor($('.nekland-editor-html'), $element)
          false

        $wrapper



    , _templates


    switchEditor = ($editor, $textarea) ->
      if $editor.is ':visible'
        $editor.hide()
        $textarea.show()
      else
        $textarea.hide()
        $editor.show()



    # Load the template
    #@after(templates.main(templates.buttons([templates.classicalButtons]), [@width(), @height()]))
    #@hide()

    $textarea = @
    $wrapper   = templates.load(@, settings.uid)
    $editor   = $wrapper.find('.nekland-editor-html');




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