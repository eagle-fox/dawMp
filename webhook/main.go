package main

import (
	"log"
	"net/http"
	"os"
	"os/exec"
)

func pushHandler(w http.ResponseWriter, r *http.Request) {
	// secret := r.Header.Get("X-Hub-Signature")
	// if secret != "sha1="+secretKey {
	// 	http.Error(w, "No autorizado", http.StatusUnauthorized)
	// 	return
	// }

	if r.Method != http.MethodPost {
		http.Error(w, "Método no permitido", http.StatusMethodNotAllowed)
		return
	}

	err := r.ParseForm()
	if err != nil {
		http.Error(w, "Error al analizar el formulario", http.StatusBadRequest)
		return
	}

	gitUsername := os.Getenv("GIT_USERNAME")
	gitToken := os.Getenv("GIT_TOKEN")

	// Clonar github.com/peseoane/dawMp la branch dev-docker
	cmd := exec.Command("git", "clone", "-b", "dev-docker", "https://"+gitUsername+":"+gitToken+"@github.com/peseoane/dawMp")
	err = cmd.Run()
	if err != nil {
	    http.Error(w, "Error al clonar el repositorio", http.StatusInternalServerError)
	    log.Println(err)
	    return
	}

	log.Println("Repositorio clonado")

	// Ejecutar docker-compose up -d --force-recreate --build

	cmd = exec.Command("docker-compose", "up", "-d", "--force-recreate", "--build")
	cmd.Dir = "dawMp"
	err = cmd.Run()
	if err != nil {
	    http.Error(w, "Error al ejecutar docker-compose", http.StatusInternalServerError)
	    log.Println(err)
	    return
	}

	log.Println("docker-compose ejecutado")

	return

}

func main() {
	log.SetOutput(os.Stdout)
	http.HandleFunc("/push", pushHandler)
	log.Fatal(http.ListenAndServe(":2002", nil))
}