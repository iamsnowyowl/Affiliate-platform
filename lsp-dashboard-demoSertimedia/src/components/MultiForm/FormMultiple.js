import React from 'react';
import { Row, Col, Button } from 'reactstrap';

import '../../../css/FormMultiple.css';
import { multiLanguage } from '../../../components/Language/getBahasa';
const FormMultiple = props => {
  return props.unit_competence.map((val, idx) => {
    let unit_codeID = `unit_code-${idx}`,
      titleID = `title-${idx}`,
      skkniID = `skkni-${idx}`;
    const genap = idx % 2;

    return (
      <div key={idx}>
        <Row style={{ backgroundColor: genap === 0 ? '#73818f38' : 'white' }}>
          <Col md="2">
            <label htmlFor={unit_codeID} style={{ marginTop: '20px' }}>
              {multiLanguage.codeUnit}
              <span className="required">*</span>
            </label>
          </Col>
          <Col md="9">
            <input
              type="text"
              name={unit_codeID}
              data-id={idx}
              id={unit_codeID}
              defaultValue={props.unit_competence[idx].unit_code}
              className="unit_code"
              style={{ width: '100%' }}
            />
          </Col>
          <Col md="2">
            <label htmlFor={titleID} style={{ marginTop: '20px' }}>
              Judul Unit Kompetensi<span className="required">*</span>
            </label>
          </Col>
          <Col md="9">
            <input
              type="text"
              name={titleID}
              data-id={idx}
              id={titleID}
              defaultValue={props.unit_competence[idx].title}
              className="title"
              style={{ width: '100%' }}
            />
          </Col>
          <Col md="2">
            <label htmlFor={skkniID} style={{ marginTop: '20px' }}>
              SKKNI / SKKK<span className="required">*</span>
            </label>
          </Col>
          <Col md="9">
            <input
              type="text"
              name={skkniID}
              data-id={idx}
              id={skkniID}
              defaultValue={props.unit_competence[idx].skkni}
              className="skkni"
              style={{ width: '100%' }}
            />
          </Col>
          {idx !== 0 ? (
            <Col md="2">
              <Button
                onClick={() => props.remove(idx)}
                style={{ marginTop: '23%' }}
                color="danger"
              >
                <i className="fa fa-trash"> </i>
              </Button>
            </Col>
          ) : (
            ''
          )}
        </Row>
      </div>
    );
  });
};
export default FormMultiple;
