import React, * as react from 'react';
import { Col, Row, Card, CardBody, CardHeader } from 'reactstrap';
import '../../css/Dashboard.css';
import '../../css/loaderDataTable.css';
import { multiLanguage } from '../../components/Language/getBahasa';
import CarouselDashboard from '../../components/Carousel/Carousel';
import AssessmentDashboard from './AssessmentDashboard';
import { getRole } from '../../components/config/config';

import '../../css/Dashboard.css';
import Schedule from '../Schedule/Schedule';
import ScheduleAsesi from '../Schedule/ScheduleAsesi';
class Dashboard extends react.Component {
  render() {
    return getRole() === 'ACS' ? (
      <Schedule />
    ) : getRole() === 'APL' ? (
      <ScheduleAsesi />
    ) : (
      <div className="animated fadeIn">
        <Row className="textJadwal">
          <Col>
            <Card>
              <CardHeader
                style={{ textAlign: 'center' }}
              >{`${multiLanguage.schedule} ${multiLanguage.Assessment}`}</CardHeader>
              <CardBody>
                <AssessmentDashboard />
              </CardBody>
            </Card>
          </Col>
        </Row>
        <Row>
          <Col>
            <Card
              style={{
                backgroundImage:
                  'linear-gradient(to bottom, #ffff 30%, #e4e5e6 100%)',
                border: 'none'
              }}
            >
              <CardBody>
                <CarouselDashboard />
              </CardBody>
            </Card>
          </Col>
        </Row>
      </div>
    );
  }
}

export default Dashboard;
