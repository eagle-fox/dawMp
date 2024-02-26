package main

import (
	"database/sql"
	"encoding/binary"
	"fmt"
	"github.com/go-faker/faker/v4"
	_ "github.com/go-faker/faker/v4/pkg/options"
	_ "github.com/go-sql-driver/mysql"
	"golang.org/x/crypto/bcrypt"
	"log"
	"net"
	"runtime"
	"strconv"
	"sync"
	"time"
)

func ipToUint(ip net.IP) uint32 {
	if len(ip) == 16 {
		return binary.BigEndian.Uint32(ip[12:16])
	}
	return binary.BigEndian.Uint32(ip)
}

type Point struct {
	Latitude  float64
	Longitude float64
}

type User struct {
	FirstName      string
	SecondName     string
	LastName       string
	SecondLastName string
	Email          string
	Password       interface{}
}

type Client struct {
	User  int64
	IPv4  string
	Token string
}

type Device struct {
	User    int64
	Token   string
	Icon    string
	Name    string
	Species string
}

type Data struct {
	Device   int64
	Location Point
}

func main() {
	host := "localhost"
	username := "root"
	password := "javaescripto"
	dbname := "eagle-fox"

	port := "2005" // replace with your port
	dataSourceName := fmt.Sprintf("%s:%s@tcp(%s:%s)/%s", username, password, host, port, dbname)
	db, err := sql.Open("mysql", dataSourceName)
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()
	type esp struct {
		StringESP string `faker:"lang=esp"`
	}

	totalUsers := 128
	totalClientsPerUser := 128
	totalDevicesPerUser := 128
	totalDataPerDevice := 128

	totalOperations := totalUsers * (totalClientsPerUser + totalDevicesPerUser*totalDataPerDevice)
	completedOperations := 0

	runtime.GOMAXPROCS(runtime.NumCPU())
	sem := make(chan bool, 22) //  runtime.NumCPU()

	var wg sync.WaitGroup
	startTime := time.Now()
	// swtich locale to Spanish in faker
	for i := 0; i < totalUsers; i++ {
		wg.Add(1)
		go func(i int) {
			defer wg.Done()

			// Adquirir un token del semáforo tipo tokenring
			sem <- true

			user := User{
				FirstName:      faker.FirstName(),
				SecondName:     faker.FirstName(),
				LastName:       faker.LastName(),
				SecondLastName: faker.LastName(),
				Email:          faker.Email(),
			}
			user.Password, err = bcrypt.GenerateFromPassword([]byte("password"), bcrypt.DefaultCost)
			if err != nil {
				log.Fatal(err)
			}

			res, err := db.Exec(`INSERT INTO user (nombre, nombre_segundo, apellido_primero, apellido_segundo, email, password) VALUES (?, ?, ?, ?, ?, ?)`,
				user.FirstName, user.SecondName, user.LastName, user.SecondLastName, user.Email, user.Password)
			if err != nil {
				log.Fatal(err)
			}

			userID, err := res.LastInsertId()
			if err != nil {
				log.Fatal(err)
			}

			completedOperations++

			for j := 0; j < totalClientsPerUser; j++ {
				client := Client{
					User:  userID,
					IPv4:  strconv.Itoa(int(ipToUint(net.ParseIP(faker.IPv4())))),
					Token: faker.UUIDDigit(),
				}

				_, err := db.Exec(`INSERT INTO clients (user, ipv4, token) VALUES (?, ?, ?)`,
					client.User, client.IPv4, client.Token)
				if err != nil {
					log.Fatal(err)
				}

				completedOperations++
			}

			for j := 0; j < totalDevicesPerUser; j++ {
				device := Device{
					User:    userID,
					Token:   faker.UUIDDigit(),
					Icon:    faker.Word(),
					Name:    faker.Word(),
					Species: faker.Word(),
				}

				res, err := db.Exec(`INSERT INTO iot_devices (user, token, icon, name, especie) VALUES (?, ?, ?, ?, ?)`,
					device.User, device.Token, device.Icon, device.Name, device.Species)
				if err != nil {
					log.Fatal(err)
				}

				deviceID, err := res.LastInsertId()
				if err != nil {
					log.Fatal(err)
				}

				completedOperations++

				for k := 0; k < totalDataPerDevice; k++ {
					data := Data{
						Device: deviceID,
						Location: Point{
							Latitude:  faker.Latitude(),
							Longitude: faker.Longitude(),
						},
					}

					_, err := db.Exec(`INSERT INTO iot_data (device, Latitude, Longitude) VALUES (?, ?, ?)`,
						data.Device, data.Location.Latitude, data.Location.Longitude)
					if err != nil {
						log.Fatal(err)
					}

					completedOperations++

					if completedOperations%100 == 0 {
						elapsed := time.Since(startTime).Seconds()
						opsPerSecond := float64(completedOperations) / elapsed
						etaSeconds := float64(totalOperations-completedOperations) / float64(completedOperations) * elapsed
						etaFormatted := time.Duration(etaSeconds) * time.Second
						log.Printf("ETA %s  Progress: %.2f%%  Operations per second: %.2f", etaFormatted, float64(completedOperations)/float64(totalOperations)*100, opsPerSecond)
					}
				}
			}

			// hay que liberar el semaforo
			<-sem
		}(i)
	}

	wg.Wait()

	fmt.Println("Random data created")
}
