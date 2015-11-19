# Code Climate PHP Mess Detector (PHPMD) Engine

[![Code Climate](https://codeclimate.com/github/codeclimate/codeclimate-phpmd/badges/gpa.svg)](https://codeclimate.com/github/codeclimate/codeclimate-phpmd)

`codeclimate-phpmd` is a Code Climate Engine that wraps the PHP Mess Detector (PHPMD) static analysis tool.

### Installation

1. If you haven't already, [install the Code Climate CLI](https://github.com/codeclimate/codeclimate).
2. Run `codeclimate engines:enable phpmd`. This command both installs the engine and enables it in your `.codeclimate.yml` file.
3. You're ready to analyze! Browse into your project's folder and run `codeclimate analyze`.

###Config Options

Format the values for these config options per the [PHPMD documentation](http://phpmd.org/documentation/index.html).

* file_extensions - This is where you can configure the file extensions for the files that you want PHPMD to analyze.
* rulesets - This is the list of rulesets that you want PHPMD to use while analyzing your files.

###Sample Config

    exclude_paths:
     - "/examples/**/*"
    engines:
      phpmd:
        enabled: true
        config:
          file_extensions: "php"
          rulesets: "unusedcode"
    ratings:
      paths:
      - "**.php"

### Need help?

For help with PHPMD, [check out their documentation](http://phpmd.org/documentation/index.html).

If you're running into a Code Climate issue, first look over this project's [GitHub Issues](https://github.com/phpmd/phpmd/issues), as your question may have already been covered. If not, [go ahead and open a support ticket with us](https://codeclimate.com/help).
