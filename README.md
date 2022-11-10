# NOTES FOR ME

-   Snowflake as primary id
-   Tweet have an edit_history_tweet_ids as json that represent the editing history for this tweet
-   Tweet have an object edit_controls that represents the edits remaining, From the official twitter api: \_when present, this indicates how much longer the Tweet can be edited and the number of remaining edits. Tweets are only editable for the first 30 minutes after creation and can be edited up to five times\*.
-   Dive in to understand context_annotations and make Entity recognition/extraction, topical analysis
-   Tweets handle the lang with BCP47 language tag https://www.techonthenet.com/js/language_tags.php
-   Tweets can be judged and reviewed as possibly sensitive
-   Tweet reply settings can be defined as: everyone, mentioned_users, followers
-   The tweet has a source field that reflect where was created.
