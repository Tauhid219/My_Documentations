Question: 
I want to show data from mysql in my laravel app. I insert data using rich text editor, but when showing, it shows with tags. 

Answer: 

To display rich text data without showing HTML tags in Laravel, you can use the `{!! !!}` syntax in Blade, which tells Laravel to render the HTML safely without escaping it. Here’s how:

```blade
{!! $yourVariable !!}
```

However, be cautious when using this approach to avoid potential XSS vulnerabilities. If you're sure the input is safe (like from a trusted rich text editor), this will render the HTML correctly.

Alternatively, if you want to strip HTML tags for plain text display, you can use:

```blade
{{ strip_tags($yourVariable) }}
```

Let me know if you need further customization!

Question: 
{{ $data->content }} 

Answer: 

If you're using `{{ $data->content }}`, Laravel will automatically escape any HTML tags to prevent XSS vulnerabilities, which is why you're seeing the tags rendered as plain text.

To display the HTML content as intended, switch to `{!! !!}`:

```blade
{!! $data->content !!}
```

This will render the HTML without escaping, allowing your rich text formatting to appear as expected. Just ensure the content is sanitized if it's user-generated, to avoid security risks.