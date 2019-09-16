# Queues #

Module is used to create and process asynchronous tasks, saved in the queue.
  
Application should process HTTP request and generate HTTP response as fast as possible.

In case of long running tasks, for example email sending or execution of complex db query, 
response is send before all tasks are done. 
Long running task is saved in the queue and processed according priority. 

Simplest queue drivers is a database table or Redis in-memory data structure. 
Beanstalkd and Amazon SQS also can be used as queue drivers.

An option to use `cron` utility to run queue tasks can be used if no any queue driver is installed.

{{ child_pages }}

## Listing And Killing Queue Worker Processes ##

You can always check if there are any queue workers by running this command:

	ps -auxww | grep "r[u]n queued-jobs"

Typical output:

	vagrant  30884  0.4  1.0 351372 42992 pts/2    S+   09:59   0:00 php run queued-jobs

No output means that there are no active queue workers.

You can also use process ID from the second column to stop the worker.

	kill 30884 

Or you can kill all of them at once:

	ps -auxww | grep "r[u]n queued-jobs" | sed 's/^[^ ]\+ \+\([[:digit:]]\+\).*/\1/' | xargs kill

>**Note**. `sed` extracts process ID from the process list by replacing string matching `^[^ ]\+ \+\([[:digit:]]\+\).*` pattern contents from `\1` reference, that is fetch by first and only sub-expression between `\(` and `\)`. How to read the pattern:
>
>* `^` match beginning of the string
>* `[^ ]\+` match the first column until first space
>* ` \+` match space characters between first and second columns
>* `\([[:digit:]]\+\)` match second column (one or more digits) 
>* `.*` match all the rest columns