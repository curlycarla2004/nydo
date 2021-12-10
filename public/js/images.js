window.onload = () => {
    //Gestion des boutons "supprimer"
    let links= document.querySelectorAll("[data-delete]")
    
    //on boucle sur links
    for(link of links){
        //on ecoute le clic
        link.addEventListener('click', function(e){
            //On empeche la navigation
            e.preventDefault()

            //On demande confirmation
            if (confirm("Voulez-vous supprimer cette image?")) {
                //on envoie une requete Ajax vers le href du lien avec la methode DELETE
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({"_token": this.dataset.token})
                }).then(
                    //on recupere la reponse en JSON
                    response => response.json()
                ).then(data => {
                    if(data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e))
            }
        })
    }
}