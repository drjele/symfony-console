# Symfony console

**You may fork and modify it as you wish**.

Any suggestions are welcomed.

## Config example

**drjele_symfony_console.yaml**

```yaml
drjele_symfony_console:
    cron:
        config:
            template_class: \Drjele\Symfony\Console\Template\CrontabTemplate
            conf_files_dir: '%kernel.project_dir%/generated_conf/cron'
            logs_dir: '%kernel.logs_dir%/cron'
            log: true
            destination_file: 'crontab'
            heartbeat: true
        commands:
            list:
                command: '%kernel.project_dir%/bin/console list'
                schedule:
                    minute: '*'
                    hour: '*'
                    day_of_month: '*'
                    month: '*'
                    day_of_week: '*'
                log: false

    worker:
        config:
            template_class: \Drjele\Symfony\Console\Template\SupervisorTemplate
            conf_files_dir: '%kernel.project_dir%/generated_conf/worker'
            logs_dir: '%kernel.logs_dir%/worker'
            number_of_processes: 1
            auto_start: true
            auto_restart: true
            prefix: 'app-name'
            user: 'root'
        commands:
            list:
                command: '%kernel.project_dir%/bin/console list'
                number_of_processes: 2
```

If the **drjele_symfony_console.cronjob.config.heartbeat** a command with the name `heartbeat` will automatically be added, if not added by you.

## For custom templates

Create a template service to implement **Drjele\Symfony\Console\Contract\TemplateInterface** and add to your **services.yaml** this config:

```yaml
services:
    _instanceof:
        Drjele\Symfony\Console\Contract\TemplateInterface:
            tags: [ 'drjele.symfony.console.template' ]
```

## Dev

```shell
git clone git@gitlab.com:drjele-symfony/console.git
cd console

rm -rf .git/hooks && ln -s ../dev/git-hooks .git/hooks

./dc build && ./dc up -d
```
