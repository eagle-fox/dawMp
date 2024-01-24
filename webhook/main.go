package main

import (
	"fmt"
	"net/http"
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

	cmd := exec.Command("docker-compose", "up", "--force-recreate", "--build", "-d")
	err = cmd.Run()
	if err != nil {
		http.Error(w, "Error al recrear Docker Compose", http.StatusInternalServerError)
		return
	}

	fmt.Fprint(w, "Acción de recrear Docker Compose completada exitosamente")

	cmd = exec.Command("git", "fetch")
	err = cmd.Run()
	if err != nil {
		http.Error(w, "Error al hacer git fetch", http.StatusInternalServerError)
		return
	}

	fmt.Fprint(w, "git fetch completado exitosamente")
}

func main() {
	http.HandleFunc("/push", pushHandler)
	http.ListenAndServe(":2002", nil)
}
