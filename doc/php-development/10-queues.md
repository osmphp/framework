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