

structure de routage
``` routage
/ , ['filter' => 'auth']  -- >  routes accessibles uniquement aux utilisateurs connectés
    user/ ['filter' => 'userAuth'] # ce qui a une user_id == 2
        dashboard
        imc
        etc.

    admin/ ['filter' => 'adminAuth']  # ce qui a une user_id == 1
        dashboard
        regime
            /create
            /store
            /edit(:num)
            /update(:num)
            /delete(:num)


```

c'est moi qui va faire les filtrages tout marchera apres ca .
je ne vais pas toucher a la partie registre et login , je vais juste faire les filtrages pour les routes admin et user 


dans view/
        
        admin
            /dashboard
            /regime

        user
            /dashboard
            /regime
            /objectifs
            /register
        auth
        errors
        layouts
