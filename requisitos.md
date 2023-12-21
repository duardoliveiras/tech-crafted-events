#### 2.1. Administrator

| Identifier | Story name                        | Priority | Description                                                                                                                                                                |
| ---------- | --------------------------------- | -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| AD01 [✔]   | Administrator Account             | High     | As an Administrator User, I aim to create an admin account, granting me privileged access to Tech Crafted data and functionalities needed to maintain the service.         |
| AD02 [✔]   | Administrate User-created content | High     | As an administrator, I need to have privileged access to user, event, and forum information, so that I may regulate the content published onto the service.                |
| AD03 [✖]   | Block and unblock users           | Medium   | As an administrator, I want the ability to block and unblock users, so that I may punish users improperly using the platform, while being able to rectify any unjust bans. |
| AD04 [✔]   | Delete event or user              | Medium   | As an administrator, I want the capability to delete both users and events, enabling me to enforce penalties for inappropriate or hateful behavior.                        |
| AD05 [✔]   | Manage events report              | Low      | As an Administrator, I want to manage event reports, so I can take action against inappropriate or hateful behavior.                                                       |

#### 2.2. User

| Identifier | Story name            | Priority | Description                                                                                                                                                                    |
| ---------- | --------------------- | -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| US01 [✔]   | View Homepage         | High     | As a user, I want to be able to seamlessly navigate Tech Crafted's homepage, where I can choose what to do.                                                                    |
| US02 [✔]   | View events           | High     | As a user, I want the ability to easily access information when viewing events on Tech Crafted.                                                                                |
| US03 [✔]   | Browse Events         | High     | As a user, I want to be able to browse events so that I may find new events that interest me.                                                                                  |
| US04 [✔]   | Search Events         | High     | As a user, I want to be able to search for events through text so that I may locate specific events.                                                                           |
| US05 [✔]   | Explore Events by Tag | Low      | As a user, I want to be able to search for events through the use of tags so that I may easily find events I like based on specific preferences.                               |
| US06 [✔]   | View About us page    | Low      | As a user, I want to easily get to know more about this platform on the About Us page, including details about its development, purpose, and creators.                         |
| US07 [✔]   | View FAQ/Help page    | Low      | As a user, I desire to access useful information about Tech Crafted's FAQs and help resources to better understand the platform's functionality and how to use it effectively. |

#### 2.3. Visitor

| Identifier | Name              | Priority | Description                                                                                                    |
| ---------- | ----------------- | -------- | -------------------------------------------------------------------------------------------------------------- |
| VS01 [✔]   | Sign up           | High     | As a visitor, I aim to register in the system, enabling me to authenticate myself within it.                   |
| VS02 [✔]   | Sign in           | High     | As a visitor, I desire to authenticate myself within the system to gain access to attendee or organize events. |
| VS03 [✔]   | Sign up OAuth API | Low      | As a visitor, I want to sign up using my Google account.                                                       |
| VS04 [✔]   | Sign in OAuth API | Low      | As a visitor, I want to authenticate with my Google account.                                                   |

#### 2.4. Authenticated User

| Identifier | Story name                          | Priority | Description                                                                                                                                                            |
| ---------- | ----------------------------------- | -------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| AU01 [✔]   | Create event                        | High     | As a user, I want to be able to create my own event, so that I may promote it and give people an easy way to get admission to it.                                      |
| AU02 [✔]   | Purchase Ticket to Event Stripe API | Medium   | As a user, I want to be able to buy tickets to events. Then I will be redirected to a Stripe window to make the payment.                                               |
| AU03 [✔]   | View Profile                        | Medium   | As a user, I desire to have my information in my own account to be able to subscribe and create events.                                                                |
| AU04 [✔]   | Edit Profile                        | Medium   | As a user, I wish to update my information when it becomes outdated.                                                                                                   |
| AU05 [✔]   | Suporte Profile Picture             | Low      | As a user, I wish to upload a picture of myself to my profile in order to have more information about myself.                                                          |
| AU06 [✖]   | Manage my events                    | Low      | As a user, I want to easily be aware of information of the events I'm subscribed to, in order to keep myself on top of any news regarding it.                          |
| AU07 [✔]   | View Personal Notifications         | Low      | As a user, I want to be notified by Tech Crafted's platform about my events and forums, so as to be informed of any important information that concerns me.            |
| AU08 [✔]   | Report an event                     | Low      | As a user, I want to be able to report an account or an event that is against the platform guidelines, in order to help the administrators moderate the service.       |
| AU09 [✖]   | Manage Invitations Sent/Received    | Low      | As a user, I want to be able to have control over the invitations I have sent or received in Tech Crafted's platform, so that I may moderate who can attend my events. |
| AU10 [✔]   | Logout                              | Low      | As a user, I want to be able to log out of my account by clicking on a "Logout" link in the user interface so that I can securely end my session.                      |

#### 2.5. Event Organizer

| Identifier | Name                       | Priority | Description                                                                                                                                                                                  |
| ---------- | -------------------------- | -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| EO01 [✔]   | Edit Event Details         | Medium   | As an event organizer, I would like to edit my event's information, in case some new update to it must be published.                                                                         |
| EO02 [✖]   | Invite Users               | Medium   | As an event organizer, I desire to invite other users to attend my event.                                                                                                                    |
| EO03 [✔]   | Manage Available Tickets   | Medium   | As an event organizer, I seek the capability to manage the availability of tickets for my event, as there are occasions when I need to control ticket availability based on various factors. |
| EO04 [✔]   | Cancel an event            | High     | As an event organizer, I aim to have the option to cancel events, as there are situations when unforeseen circumstances necessitate event cancellations or rescheduling.                     |
| EO05 [✖]   | Manage Event Participants  | Low      | As an event organizer, I want to have control over who is in my event, so as to prevent undesired users from attending.                                                                      |
| EO06 [🚫]  | Create Polls               | Low      | As an event organizer, I desire to create polls to help my public interact with the event.                                                                                                   |
| EO07 [✖]   | Manage Event's Visibility  | Low      | As an event organizer, I need the ability to manage my event's visibility because there are times when I want to control who can view it or keep it private for specific reasons.            |
| EO08 [✖]   | Access Statistics on Event | Low      | As an event organizer, I want access to statistics regarding event participation because it helps me gather insights into attendee engagement and assess the success of my events.           |

#### 2.6. Attendee

| Identifier | Name                  | Priority | Description                                                                                                                                                                            |
| ---------- | --------------------- | -------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| AT01 [✔]   | View Event's Messages | High     | As an attendee, my goal is to have the ability to view and participate in forum discussions before and during the event, enhancing my engagement and interaction with other attendees. |
| AT02 [✔]   | Add comments          | High     | As an attendee, I desire the capability to add comments during the event, fostering interactive engagement and enabling me to share insights and opinions with fellow participants.    |
| AT03 [✔]   | Upload files          | Medium   | As an attendee, I wish to have the option to upload files during the event, facilitating the sharing of relevant documents and resources with other participants.                      |
| AT04 [✔]   | Leave event           | Medium   | As an attendee, I want the ability to leave an event when needed, ensuring I have control over my event participation and availability.                                                |
| AT05 [✔]   | Edit Comment          | Medium   | As an attendee, I desire the ability to edit my comments during the event, enabling me to refine and update my contributions as necessary.                                             |
| AT06 [✔]   | Delete Comment        | Medium   | As an attendee, I seek the option to delete my comments during the event, allowing me to manage and remove any contributions that may no longer be relevant or appropriate.            |
| AT07 [🚫]  | Answer Polls          | Low      | As an attendee, I aspire to be able to respond to polls during the event, allowing me to actively participate in interactive surveys and contribute to the event's discussions.        |

### 3. Supplementary Requirements

#### 3.1. Business rules

| Identifier | Name                                   | Description                                                                                                                                              |
| ---------- | -------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------- |
| BR01 [🚫]  | Event Visibility Settings              | Events can be public or private. Private events are not shown in search results.                                                                         |
| BR02 [✖]   | Administrator Account Distinctiveness  | Administrator accounts are independent of the user accounts, i.e. they cannot create or participate in events.                                           |
| BR03 [✔]   | Data Anonymization on Account Deletion | Upon account deletion, shared user data (e.g. comments, reviews, likes) is kept but is made anonymous.                                                   |
| BR04 [✔]   | Edit/delete comment                    | An authenticated user will only be able to edit or delete their own comments.                                                                            |
| BR05 [✔]   | Attend an event                        | An authenticated user will only be able to participate in an event if the current date is less than the event date and there are still places available. |
| BR06 [✔]   | New event date                         | A new event can only be created with a date later than at least 1 day from the current day.                                                              |
