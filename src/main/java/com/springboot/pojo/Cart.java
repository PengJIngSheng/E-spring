package com.springboot.pojo;

import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.stereotype.Component;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Component
public class Cart {

    @Id
    @GeneratedValue(strategy = GenerationType.AUTO)

    private String custid;

    private String productid;

    private String productname;

    private Double productprice;

    private Integer productquantity;

    private Double totalprice;
}
