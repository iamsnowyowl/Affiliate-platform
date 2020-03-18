import React, { Component } from 'react';
import { CardDeck, Card, CardBody } from 'reactstrap';
import { Carousel } from 'react-responsive-carousel';

import Users from '../CardDashboard/Users';
import Assessors from '../CardDashboard/Assessors';
import TUK from '../CardDashboard/TUK';
import Asesi from '../CardDashboard/Asesi';
import MainSchema from '../CardDashboard/MainSchema';
import SubSchema from '../CardDashboard/SubSchema';
import { getRole } from '../config/config';

import 'react-responsive-carousel/lib/styles/carousel.min.css';
import '../../css/Dashboard.css';

export default class CarouselDashboard extends Component {
  render() {
    return (
      <Carousel
        showIndicators={false}
        showStatus={false}
        showThumbs={false}
        interval={3000}
        infiniteLoop={true}
      >
        <div>
          {getRole() === 'ACS' ? (
            <CardDeck>
              <Card className="text-white img-accessor">
                <CardBody>
                  <TUK />
                </CardBody>
              </Card>
              <Card className="text-white img-accessor">
                <CardBody>
                  <MainSchema />
                </CardBody>
              </Card>
              <Card className="text-white img-accessor">
                <CardBody>
                  <SubSchema />
                </CardBody>
              </Card>
            </CardDeck>
          ) : (
            <CardDeck>
              <Card className="text-white img-accessor">
                <CardBody>
                  <Users />
                </CardBody>
              </Card>
              <Card className="text-white img-accessor">
                <CardBody>
                  <Assessors />
                </CardBody>
              </Card>
              <Card className="text-white img-accessor">
                <CardBody>
                  <TUK />
                </CardBody>
              </Card>
              <Card className="text-white img-accessor">
                <CardBody>
                  <Asesi />
                </CardBody>
              </Card>
            </CardDeck>
          )}
        </div>
      </Carousel>
    );
  }
}
