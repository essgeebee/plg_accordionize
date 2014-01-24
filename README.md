#plg_accordionize


Accordionize plugin for Joomla 1.5 - 2.5

Allows you to create an accordion widgets within any article.

##Example

Your article content:

```html
{accordionize}
<h3>A heading</h3>
<p>This paragraph will be part of the first accordion item.</p>
<p>And so will this one!</p>
<p>And this one too - until the next h3 is found.</p>
<h3>A second heading</h3>
<p>Text for accordion item #2</p>
<p>More text for accordion item #2</p>
{/accordionize}
```

Is transformed into:

```html
<div class="accordionize">
  <h3>A heading</h3>
  <div class="accordionContent">
    <p>This paragraph will be part of the first accordion item.</p>
    <p>And so will this one!</p>
    <p>And this one too - until the next h3 is found.</p>
  </div>
  <h3>A second heading</h3>
  <div class="accordionContent">
    <p>Text for accordion item #2</p>
    <p>More text for accordion item #2</p>
  </div>
</div>
```

The plugin uses Mootools' Fx.Accordion plugin, and ensures that the mootools-more.js is loaded.

###Configuration

All of these are configurable plugin parameters, and will not break the functionality of the accordion.

* **Heading Level**
  * You can select the heading level (h1, h2, h3, h4, h5, h6) to use for each accordion item's clickable titlebar (default: h3)

* **Wrapper class**
  * This is the wrapper class used by the div that surrounds all of the accordion items (default: 'accordionize')

* **Content Class**
  * This is the wrapper class that surrounds the accordion item's content (default: 'accordionContent')

