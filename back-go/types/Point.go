package types

import "fmt"

type Point struct {
	Latitude  float64
	Longitude float64
}

func (p Point) String() string {
	return fmt.Sprintf("POINT(%f %f)", p.Latitude, p.Longitude)
}
