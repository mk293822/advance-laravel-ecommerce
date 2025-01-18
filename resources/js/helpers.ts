import CartItem from "@/Components/app/CartItem";
import {CartItems} from "@/types";

export const arraysAreEqual = (arr1: any[], arr2: any[])=>{
  if(arr1.length !== arr2.length) return false;

  return arr1.every((value, index)=> value === arr2[index]);

}

export const productRoute = (item: CartItems)=>{
  const params = new URLSearchParams();
  // console.log(item);
  Object.entries(item.option_ids).forEach(([typeID, optionId])=> {
      params.append(`options[${typeID}]`, optionId + '')
    })

  return route('product.show', item.slug) + '?' + params.toString();
}
