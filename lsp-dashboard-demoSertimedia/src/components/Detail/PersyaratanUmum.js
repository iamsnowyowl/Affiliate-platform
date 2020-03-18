import React, { Component } from "react";
import { Link } from "react-router-dom";
import { Card, CardHeader, CardBody, CardFooter, Row, Col } from "reactstrap";
import ListPersyaratanUmum from "../ListTables/ListPersyaratanUmum";
import { multiLanguage } from "../Language/getBahasa";

class PersyaratanUmum extends Component {
  render() {
    const {
      applicant_id,
      assessment_id,
      assessment_applicant_id
    } = this.props.match.params;
    return (
      <div>
        <Card>
          <CardHeader>
            <Row>
              <Col md="5">
                <h5
                  style={{
                    textDecoration: "underline",
                    color: "navy"
                  }}
                >
                  Data Persyaratan Umum
                </h5>
              </Col>
            </Row>
          </CardHeader>
          <CardBody>
            <ListPersyaratanUmum
              applicant_id={applicant_id}
              assessment_id={assessment_id}
              assessment_applicant_id={assessment_applicant_id}
            />
          </CardBody>
          <CardFooter>
            <Link to={"/asesi"}>
              <button className="btn btn-danger" title={multiLanguage.back}>
                <i className="fa fa-chevron-left" /> {multiLanguage.back}
              </button>
            </Link>
          </CardFooter>
        </Card>
      </div>
    );
  }
}

export default PersyaratanUmum;
