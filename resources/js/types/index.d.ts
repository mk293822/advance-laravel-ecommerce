import { Config } from "ziggy-js";

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
}

export type Image = {
  id: number;
  thumb: string;
  small: string;
  large: string;
};

export type VariationTypeOption = {
  id: number;
  name: string;
  images: Image[];
  type: VariationType;
};

export type VariationType = {
  id: number;
  name: string;
  type: "select" | "radio" | "image";
  options: VariationTypeOption[];
};

export type Product = {
  id: number;
  title: string;
  slug: string;
  price: number;
  quantity: number;
  image: string;
  images: Image[];
  description: string;
  short_description: string;
  user: {
    name: string;
    id: number;
  };
  department: {
    id: number;
    name: string;
  };
  variationTypes: VariationType[];
  variations: Array<{
    id: number;
    variation_type_option_ids: number[];
    price: number;
    quantity: number;
  }>;
};

export type CartItems = {
  title: string,
  product_id: number,
  id: number,
  price: number,
  quantity: number,
  slug: string,
  image:string,
  options_ids: Record<string, number>,
  options: VariationTypeOption[]
}

export type PaginationProps<T> = {
  data: Array<T>;
};

export type GroupedCartItems = {
  user: User,
  items: CartItems[],
  totalQuantity: number,
  totalPrice: number,
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User;
  };
  ziggy: Config & { location: string };
  csrf_token: string;
  totalQuantity: number;
  totalPrice: number;
  miniCartItems: CartItems[];
};
