Make your own administration
============================

To make an ultra-basic administration (but working !) you just have to define some settings in the `nekland_admin.yml` file that you have to save in your `Resource/config` bundle directory.

Here is a basic example of what you can define:

```
nekland_admin:
    resources:
        event:
            name: event
            classes:
                model: App\Entity\Event
            properties:
                id:
                    label: "#"
                    editable: false
                title:
                    label: "Title"
                description:
                    label: "Description"
                startDate:
                    label: "Date of start"
                endDate:
                    label: "Date of end"
```

This configuration will generate automatically the full dashboard of your application :-) .