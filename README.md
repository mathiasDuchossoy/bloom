# Backend test - questions stats calculation

Greeting fellow backend developer, the [Bloom at Work](https://www.bloom-at-work.com)'s team has a mission for you!

## The mission

Over the years, our customers have been collecting a lot of data about the well-being of their employees at work. For each question we asked, they gathered
float values (from `0.` to `10.0` included) as responses. Bloom is now offering to help them extract statistics and generate insights on these questions.   


### What we expect from you

Our customers have collected these responses into CSV files. These files contain only one column, each row being the value of a single answer. The first row containing the label of the question. From these values we
expect you to be able to compute the simple following metrics:
- the **minimum value**
- the **maximum value**
- the **mean value**, e.g. the average of all answers

The folder `resources` contains an example of such a file.

For this, we have bootstrapped a Symfony flex 4.3 project, with a _ready-to-be-implemented_ controller `BloomAtWork\Controller\QuestionStatsController`. In this controller you'll have to complete the method named `readFile` that will be used by Bloom's API. You can also create new classes if you deem them necessary. 
From there you'll allow Bloom's customers to upload their CSV files. You'll have to make sure that the files uploaded by the clients are valid, would you encounter a file with invalid values you would filter out those invalid values and process only the valid values contained by the file.

Finally you'll extract these values, compute them into an implementation of the abstract class `BloomAtWork\Model\AbstractQuestion` and return the result as JSON according to this contract:
```
    "question": {
        "label": "Je fais confiance à mon manager",
        "statistics": {
            "min": 6.1,
            "max": 9.7,
            "mean": 7.8
        }
    }
```

To get started, all you have to do is run `composer` to install dependencies, spin up a `symfony` web server and you'll be ready to start developing:

```bash
$ composer install

$ symfony server:start
```

As a bonus, if you're able to write some tests to help ensure the service works as expected, we'd be really grateful. We added a mock class to that end in `tests/DumbTest.php`.

**Good luck!** 
