# UPGRAGE

## dev-develop

### Interface of TaskExecutionRepository

*This hint is only important to you if you have extended php-task with your own storage implementation.*

The method `findScheduled` was replaced with the method `findNextScheduled` which only returns the next scheduled 
execution.
